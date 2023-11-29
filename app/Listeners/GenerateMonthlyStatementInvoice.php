<?php

namespace App\Listeners;

use App\Events\GenerateMonthlyStatementInvoiceEvent;
use App\Repositories\AccountStatementRepository;
use App\Repositories\AttachmentRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use App\Models\Attachment;
use Log, PDF, PDFMerger;

class GenerateMonthlyStatementInvoice
{
    protected $attachmentRepo;
    protected $accountStatementRepo;

    /**
     * Create the event listener.
     */
    public function __construct(
        AttachmentRepository $attachmentRepo,
        AccountStatementRepository $accountStatementRepo,)
    {
        $this->attachmentRepo = $attachmentRepo;
        $this->accountStatementRepo = $accountStatementRepo;
    }

    /**
     * Handle the event.
     */
    public function handle(GenerateMonthlyStatementInvoiceEvent $event): void
    {
        $statement_id = $event->statement_id;
        $pdf_data = config('pdf-data');
        $accountStatement = $this->accountStatementRepo->get($statement_id)->first();
        
        $pdf = PDF::loadView('pdf.statement-of-account-invoice', compact('accountStatement','pdf_data'));
        $dir = $accountStatement->account_statement_id;
        $file_name = $accountStatement->account_statement_id.'-invoice.pdf';

        if (File::exists(base_path() .'/storage/app/public/temp/'.$dir)) {
            File::deleteDirectory(base_path() .'/storage/app/public/temp/'.$dir);
        }
        File::ensureDirectoryExists(base_path() .'/storage/app/public/temp/'.$dir);
        $pdf->save(base_path() .'/storage/app/public/temp/'.$dir.'/'.$file_name);

        $uniqueid = generateUniqueID(new Attachment, 4);
        $data = [
            'attachment_id' => $uniqueid,
            'attachmentresource_type' => 'statement-account-invoice',
            'attachmentresource_id' => $accountStatement->id,
            'attachment_directory' => $dir,
            'attachment_uniqiueid' => $dir,
            'attachment_filename' => $file_name,

        ];
        $this->attachmentRepo->clear($accountStatement->id,'statement-account-invoice');
        if(!$file = $this->attachmentRepo->process($data)){
            abort(409, 'Something went wrong');
        }
    }
}
