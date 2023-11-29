<?php

namespace App\Listeners;

use App\Events\GenerateCreditNoteInvoiceEvent;
use App\Repositories\AttachmentRepository;
use App\Repositories\CreditNoteRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use App\Models\Attachment;
use Log, PDF, PDFMerger;

class GenerateCreditNoteInvoice
{

    protected $creditNoteRepo;
    protected $attachmentRepo;

    /**
     * Create the event listener.
     */
    public function __construct(
        CreditNoteRepository $creditNoteRepo,
        AttachmentRepository $attachmentRepo)
    {
        $this->creditNoteRepo = $creditNoteRepo;
        $this->attachmentRepo = $attachmentRepo;
    }

    /**
     * Handle the event.
     */
    public function handle(GenerateCreditNoteInvoiceEvent $event): void
    {
        $cn_id = $event->cn;
        $uniqueid = generateUniqueID(new Attachment, 4);
        $pdf_data = config('pdf-data');
        $creditNote = $this->creditNoteRepo->get($cn_id)->first();

          //if GST is applied
        $initial_amount = $creditNote->amount / (1 + ( $creditNote->gst_percent/ 100));

        $creditNote->gst_amount = $initial_amount;
                
        $pdf = PDF::loadView('pdf.credit-notes-invoice', compact('creditNote','pdf_data'));

        $dir = $creditNote->note_id;
        $file_name = $creditNote->note_id.'-invoice.pdf';

        if (File::exists(base_path() .'/storage/app/public/temp/'.$dir)) {
            File::deleteDirectory(base_path() .'/storage/app/public/temp/'.$dir);
        }
        File::ensureDirectoryExists(base_path() .'/storage/app/public/temp/'.$dir);
        $pdf->save(base_path() .'/storage/app/public/temp/'.$dir.'/'.$file_name);

        $data = [
            'attachment_id' => $uniqueid,
            'attachmentresource_type' => 'credit-note-invoice',
            'attachmentresource_id' => $creditNote->id,
            'attachment_directory' => $dir,
            'attachment_uniqiueid' => $dir,
            'attachment_filename' => $file_name
        ];
        $this->attachmentRepo->clear($creditNote->id,'credit-note-invoice');
        if(!$file = $this->attachmentRepo->process($data)){
            abort(409, 'Something went wrong');
        }
    }
}
