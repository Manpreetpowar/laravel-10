<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AccountStatement;
use App\Repositories\ClientRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\AccountStatementRepository;
use Carbon\Carbon;

class GenerateStatementOfAccount extends Command
{
    protected $clientRepo;
    protected $serviceOrderRepo;
    protected $accountStatementRepo;

    public function __construct(
        ClientRepository           $clientRepo,
        ServiceOrderRepository     $serviceOrderRepo,
        AccountStatementRepository $accountStatementRepo)
    {
        $this->clientRepo           = $clientRepo;
        $this->serviceOrderRepo     = $serviceOrderRepo;
        $this->accountStatementRepo = $accountStatementRepo;

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-statement-of-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    { 
        $clients             = $this->clientRepo->get()->get();
        foreach($clients as $client){

            $first = new Carbon('first day of this month');
            $first = $first->format('Y-m-d');
            $last = new Carbon('last day of this month');
            $last = $last->format('Y-m-d');

            $accountStatementId = generateUniqueID(new AccountStatement, 4);
            request()->merge(['filter_invoice_status'=> 'unpaid', 'filter_order_by_service_status'=> 'delivered', 'filter_order_by_status'=> 'completed', 'filter_client_id'=> $client->id, 'filter_complete_date_start'=>$first , 'filter_complete_date_end'=>$last]);

                        
            $creditAmount = 0;

            request()->merge(['filter_client_id'=> $client->id, 'filter_date_start'=>$first , 'filter_date_end'=>$last]);
            $existing_statment_of_month = $this->accountStatementRepo->get()->orderBy('id', 'desc')->first();
            
            if($existing_statment_of_month){
                $creditAmount = $existing_statment_of_month->credit_amount;
                $this->accountStatementRepo->update($existing_statment_of_month->id,['status'=>'reject']);

                request()->merge(['credit_notes' => $client->credit_notes + $creditAmount,'outstanding'=> $client->outstanding - $existing_statment_of_month->payable_amount, 'lifetime_revenue'=> $client->lifetime_revenue - $existing_statment_of_month->due_amount]);
                $this->clientRepo->update($client->id);
            }

            $unpaidCompleted_jobs = $this->serviceOrderRepo->get()->get();
            if(!$unpaidCompleted_jobs->count()){
                continue;
            }
            $totalDueAmount = 0;
            foreach ($unpaidCompleted_jobs as $job) {
                $totalDueAmount += $job->invoice->amount;
            }

            $creditNotes        = $client->notes
                ->whereIn('status', ['unredeemed', 'partial'])
                ->sortBy('created_at')
                ->sortBy('status')
                ->values();

            $amountToDeduct = $totalDueAmount - $creditAmount;
            
            $statement_credit_notes = [];
            foreach ($creditNotes as $key => $creditNote) {
                if($amountToDeduct > 0){
                    if($creditNote->status === 'partial'){
                        if ($amountToDeduct >= $creditNote->partial_amount) {
                            $partial_amount = $creditNote->partial_amount;
                            $creditAmount      += $creditNote->partial_amount;
                            $amountToDeduct    -= $creditNote->partial_amount;
                            $creditNote->status = 'redeemed';
                            $creditNote->partial_amount = 0;
                            $creditNote->save();
                        }elseif($amountToDeduct <= $creditNote->partial_amount){
                                $partial_amount     = $creditNote->partial_amount - $amountToDeduct;
                                $creditAmount      += $amountToDeduct;
                                $amountToDeduct     = 0;
                                $creditNote->partial_amount = $partial_amount;
                                $creditNote->status = 'partial';
                                $creditNote->save();
                        }
                        $statement_credit_notes[$key] = ['credit_note_id'=>$creditNote->id,'amount'=> $partial_amount];
                    }else{
                        if ($amountToDeduct >= $creditNote->amount) {
                            $partial_amount     = $creditNote->amount;
                            $creditAmount      += $creditNote->amount;
                            $amountToDeduct    -= $creditNote->amount;
                            $creditNote->status = 'redeemed';
                            $creditNote->partial_amount = 0;
                            $creditNote->save();
                        }elseif($amountToDeduct <= $creditNote->amount){
                                $partial_amount     = $creditNote->amount - $amountToDeduct;
                                $creditAmount      += $amountToDeduct;
                                $amountToDeduct     = 0;
                                $creditNote->partial_amount = $partial_amount;
                                $creditNote->status = 'partial';
                                $creditNote->save();
                        }
                        $statement_credit_notes[$key] = ['credit_note_id'=>$creditNote->id,'amount'=> $partial_amount];
                    }

                }else{
                break;
                }
            }
            // Create the account statement
            $dataToStore = [
                'account_statement_id' => $accountStatementId,
                'due_amount'           => $totalDueAmount,
                'credit_amount'        => $creditAmount,
                'payable_amount'       => $amountToDeduct,
                'client_id'            => $client->id,
            ];

            //Generate statement
            $storeStatement = $this->accountStatementRepo->create($dataToStore);
            $this->accountStatementRepo->attech_credit_notes($storeStatement->id, $statement_credit_notes);

            foreach ($unpaidCompleted_jobs as $job) {
                $storeStatement->jobs()->attach($job);
                if($amountToDeduct <= 0){
                    request()->merge(['invoice_paid_date'=>\carbon\Carbon::now()->format('Y-m-d'),'invoice_paid'=>1]);
                    $this->invoiceRepo->update($job->invoice->id);
                }

            }
            
            // Update client credit note balance and outstanding amount
            $client = $this->clientRepo->get($client->id)->first();

            $lifetimeRevenue = $client->lifetime_revenue;
            // invoices paid and accountStatementPaid
            if($amountToDeduct <= 0){
                $data = ['status'=>'paid'];
                $this->accountStatementRepo->update($storeStatement->id,$data);

                //update life time revenue
                $lifetimeRevenue = $lifetimeRevenue + $totalDueAmount;
            }

            $outstandingAmount = $storeStatement->payable_amount + $client->outstanding;
            $remainCreditNotes = $client->credit_notes - $creditAmount;
            request()->merge(['lifetime_revenue'=> $lifetimeRevenue, 'credit_notes' => $remainCreditNotes, 'outstanding'=>$outstandingAmount]);
            $this->clientRepo->update($client->id);


            event(new GenerateMonthlyStatementInvoiceEvent($storeStatement->id));
            // // send invoice to client
            Mail::to($storeStatement->client->client_email)->send(new StatementAccountInvoice($storeStatement));
        }

    }
}
