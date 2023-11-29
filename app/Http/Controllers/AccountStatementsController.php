<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Attachment;
use App\Models\AccountStatement;
use App\Repositories\ClientRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\AccountStatementRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\DestroyRepository;
use App\DataTables\ClientsDataTable;
use App\Events\GenerateMonthlyStatementInvoiceEvent;
use App\Models\CreditNote;
use Carbon\Carbon;

use Validator, Datatables;
use PDF, PDFMerger;

class AccountStatementsController extends Controller
{
    protected $clientRepo;
    protected $serviceOrderRepo;
    protected $accountStatementRepo;
    protected $invoiceRepo;

    public function __construct(
        ClientRepository           $clientRepo,
        ServiceOrderRepository     $serviceOrderRepo,
        AccountStatementRepository $accountStatementRepo,
        InvoiceRepository          $invoiceRepo)
    {
        $this->clientRepo           = $clientRepo;
        $this->serviceOrderRepo     = $serviceOrderRepo;
        $this->accountStatementRepo = $accountStatementRepo;
        $this->invoiceRepo          = $invoiceRepo;
    }


    /**
     * Show account statements
     *
     */
    public function statementsList($id)
    {

        request()->merge(['filter_client_id' => $id]);
        $statement = $this->accountStatementRepo->get();
        return Datatables::eloquent($statement)
        ->addColumn('action', function($data) {

            return '<a href="'.url('account-statement/download-soa-invoice/'.$data->id).'" class="btn btn-default btn-sm"><i class="mdi single mdi-download"></i></a>'
                .'<a href="javascript:void(0)" title="Delete Statement" class="data-toggle-action-tooltip btn btn-default confirm-action-danger"'
                .'data-confirm-title="Delete Statement" data-confirm-text="Are you sure you want delete item."'
                .'data-ajax-type="GET" data-url="'.url('account-statement/destroy-soa/'.$data->id).'"> <i class="mdi single mdi-trash-can-outline"></i> </a>';
            })
            ->editColumn('due_amount', function ($data) {
                return '$' . $data->due_amount;

            })
            ->editColumn('credit_amount', function ($data) {
                return '$' . $data->credit_amount;
            })
            ->editColumn('payable_amount', function ($data) {
                return '$' . $data->payable_amount;
            })
            ->editColumn('status', function ($data) {
                if($data->status == 'paid'){
                    $html = '<button type="button" title="Status Change" class="data-toggle-action-tooltip btn btn-default confirm-action-success"'
                            .'data-confirm-title="Change Status" data-confirm-text="Are you sure you want to change status."'
                            .'data-ajax-type="POST" data-url="'. url('account-statement/status-change' , $data->id).'">'
                            .'<span class="text-success">Paid</span>'
                        .'</button>';
                    return $html;
                }elseif($data->status == 'unpaid'){
                        $html = '<button type="button" title="Status Change" class="data-toggle-action-tooltip btn btn-default confirm-action-success"'
                        .'data-confirm-title="Change Status" data-confirm-text="Are you sure you want to change status."'
                        .'data-ajax-type="POST" data-url="'. url('account-statement/status-change' , $data->id).'">'
                        .'<span class="text-danger">Unpaid</span>'
                    .'</button>';
                   return $html;
                }else{
                    return '<span class="text-default text-center w-100">Rejected</span>';
                }
            })
            ->editColumn('created_at', function ($data) {
                return runtimeDate($data->created_at);
            })
            ->rawColumns(['action','status'])
            ->make(true);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function accountStatementView($id)
    {
        $first = new Carbon('first day of this month');
        $first = $first->format('Y-m-d');
        $last = new Carbon('last day of this month');
        $last = $last->format('Y-m-d');
        $date = [$first,$last];

        $client = $this->clientRepo->get($id)->first();

        $html = view('pages.client.modals.generate-statement',compact('date', 'client'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXGenerateAccountStatement'];

        return response()->json($response,200);
    }

    /**
     * Genererate client account statements
     */
    public function generateAccountStatement(Request $request)
    {

        $accountStatementId = generateUniqueID(new AccountStatement, 4);
        $client             = $this->clientRepo->get($request->client_id)->first();

        request()->merge(['filter_invoice_status'=> 'unpaid', 'filter_order_by_service_status'=> 'delivered', 'filter_order_by_status'=> 'completed', 'filter_client_id'=> $client->id, 'filter_complete_date_start'=>$request->date_start , 'filter_complete_date_end'=>$request->date_end]);
        $totalDueAmount = 0;
        $unpaidCompleted_jobs = $this->serviceOrderRepo->get()->get();
        if(!$unpaidCompleted_jobs->count()){
            //hide modal
            $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
            //notice
            $ajax['notification'] = array('type' => 'error', 'value' => 'No jobs to generate statement of account');

            //ajax response & view
            return response()->json($ajax);
        }
        
        $creditAmount = 0;

        request()->merge(['filter_client_id'=> $client->id, 'filter_status' => 'unpaid', 'filter_date_start'=>$request->date_start , 'filter_date_end'=>$request->date_end]);
        $existing_statment_of_month = $this->accountStatementRepo->get()->orderBy('id', 'desc')->first();
        
        if($existing_statment_of_month){
            $creditAmount = $existing_statment_of_month->credit_amount;
            $this->accountStatementRepo->update($existing_statment_of_month->id,['status'=>'reject']);

            $client = $this->clientRepo->get($client->id)->first();
            request()->merge(['credit_notes' => $client->credit_notes + $creditAmount,'outstanding'=> $client->outstanding - $existing_statment_of_month->payable_amount]);
            $this->clientRepo->update($request->client_id);
        }

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
                        $partial_amount = $applied_amount = $creditNote->partial_amount;
                        $creditAmount      += $creditNote->partial_amount;
                        $amountToDeduct    -= $creditNote->partial_amount;
                        $creditNote->status = 'redeemed';
                        $creditNote->partial_amount = 0;
                        $creditNote->save();
                    }elseif($amountToDeduct < $creditNote->partial_amount){
                            $applied_amount = $amountToDeduct;
                            $partial_amount     = $creditNote->partial_amount - $amountToDeduct;
                            $creditAmount      += $amountToDeduct;
                            $amountToDeduct     = 0;
                            $creditNote->partial_amount = $partial_amount;
                            $creditNote->status = 'partial';
                            $creditNote->save();
                    }
                    $statement_credit_notes[$key] = ['credit_note_id'=>$creditNote->id,'amount'=> $applied_amount];
                }else{
                    if ($amountToDeduct >= $creditNote->amount) {
                        $applied_amount = $creditNote->amount;
                        $creditAmount      += $creditNote->amount;
                        $amountToDeduct    -= $creditNote->amount;
                        $creditNote->status = 'redeemed';
                        $creditNote->partial_amount = 0;
                        $creditNote->save();
                    }elseif($amountToDeduct < $creditNote->amount){
                            $applied_amount = $amountToDeduct;
                            $partial_amount     = $creditNote->amount - $amountToDeduct;
                            $creditAmount      += $amountToDeduct;
                            $amountToDeduct     = 0;
                            $creditNote->partial_amount = $partial_amount;
                            $creditNote->status = 'partial';
                            $creditNote->save();
                    }
                    
                    $statement_credit_notes[$key] = ['credit_note_id'=>$creditNote->id,'amount'=> $applied_amount];
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
            'client_id'            => $request->client_id,
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

        if($existing_statment_of_month){
            $statement_credit_notes = [];
            foreach ($existing_statment_of_month->credit_notes as $key => $note) {
                // if($note->status != 'partial'){
                    $statement_credit_notes[$key] = ['credit_note_id'=>$note->id, 'amount'=> $note->pivot->amount];
                // }
            }
            $this->accountStatementRepo->attech_credit_notes($storeStatement->id, $statement_credit_notes);
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
        $this->clientRepo->update($request->client_id);

        //Post Run Function
        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];
        //
        $ajax['dom_html'][] = array(
            'selector' => '#credit-note-amount',
            'action' => 'replace',
            'value' => $remainCreditNotes
        );


          //life time revenu change
          $ajax['dom_val'][] = array('selector' => '#lifetime_revenue', 'value' => $lifetimeRevenue);
          //hide modal
          $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
          //notice
          $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

          //ajax response & view
          return response()->json($ajax);

    }

    /**
     * Change status of account statements
     */
    public function changeStatus(Request $request)
    {
        $accountStatement = $this->accountStatementRepo->get($request->id)->first();

        $client = $this->clientRepo->get($accountStatement->client_id)->first();

        if($accountStatement->status == 'paid'){
            $status = 'unpaid';
            $invoice_paid = 0;
            $invoice_paid_date = null;
            $outstandingAmount = $accountStatement->payable_amount + $client->outstanding;
            $lifetimeRevenue = $client->lifetime_revenue - $accountStatement->payable_amount;
        }else{
            $status = 'paid';
            $invoice_paid = 1;
            $invoice_paid_date =  \carbon\Carbon::now()->format('Y-m-d');
            $outstandingAmount = $accountStatement->payable_amount - $client->outstanding;
            $lifetimeRevenue = $client->lifetime_revenue + $accountStatement->payable_amount;
        }
        
        request()->merge(['invoice_paid_date'=>$invoice_paid_date, 'invoice_paid' => $invoice_paid]);
        foreach($accountStatement->jobs as $job){
            $this->invoiceRepo->update($job->invoice->id);
        }

        $this->accountStatementRepo->update($request->id, ['status'=> $status]);

        request()->merge(['outstanding'=>$outstandingAmount, 'lifetime_revenue'=> $lifetimeRevenue]);
        $this->clientRepo->update($accountStatement->client_id);


        //life time revenu change
        $ajax['dom_val'][] = array('selector' => '#lifetime_revenue', 'value' => $lifetimeRevenue);
        
        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');
        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($ajax);
    }

    public function accountStatementfilter(){

        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $html = view('pages.client.modals.filter-soa', compact('filter'))->render();
        $response['dom_html'][] = array(
            'selector' => '#actionsModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#actionsModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXFilter'];

        return response()->json($response,200);
    }

    /**
     * download invoice
     */
    public function downloadStatementofAccountInvoice($id)
    {

        $pdf_data = config('pdf-data');
        event(new GenerateMonthlyStatementInvoiceEvent($id));
        $accountStatement = $this->accountStatementRepo->get($id)->first();

        $inv = $accountStatement->attachment_invoice->attachment_directory.'/'.$accountStatement->attachment_invoice->attachment_filename;

        return response()->download(public_path('storage/files/attachments/' .$accountStatement->attachment_invoice->attachment_directory.'/'.$accountStatement->attachment_invoice->attachment_filename));

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRepository $destroyRepo, $id)
    {
        $accountStatement = $this->accountStatementRepo->get($id)->first();

        $destroyRepo->deleteAccountStatement($accountStatement->id);

        $client = $this->clientRepo->get($accountStatement->client->id)->first();

        if($accountStatement->status != 'reject'){
            $outstandingAmount = $client->outstanding - $accountStatement->payable_amount;

            request()->merge(['outstanding'=>$outstandingAmount]);
            $this->clientRepo->update($client->id);
        }
        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($ajax);
    }


    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Account Statements'];
        return $page;
    }
}
