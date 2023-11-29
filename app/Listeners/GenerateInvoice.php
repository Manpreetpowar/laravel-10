<?php

namespace App\Listeners;

use App\Events\GenerateInvoiceEvent;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\AttachmentRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Log, PDF, PDFMerger;

class GenerateInvoice
{

    protected $serviceOrderRepo;
    protected $attachmentRepo;

    /**
     * Create the event listener.
     */
    public function __construct(ServiceOrderRepository $serviceOrderRepo, AttachmentRepository $attachmentRepo)
    {
        $this->serviceOrderRepo = $serviceOrderRepo;
        $this->attachmentRepo = $attachmentRepo;
    }

    /**
     * Handle the event.
     */
    public function handle(GenerateInvoiceEvent $event): void
    {
        $job = $event->job;

        $pdf_data = config('pdf-data');
        $serviceOrder = $this->serviceOrderRepo->get($job->id)->first();

        $pdf = PDF::loadView('pdf.service-order-invoice', compact('serviceOrder','pdf_data'));

        $dir = $serviceOrder->service_order_id;
        $file_name = $serviceOrder->service_order_id.'-invoice.pdf';

        if (File::exists(base_path() .'/storage/app/public/temp/'.$dir)) {
            File::deleteDirectory(base_path() .'/storage/app/public/temp/'.$dir);
        }
        File::ensureDirectoryExists(base_path() .'/storage/app/public/temp/'.$dir);
        $pdf->save(base_path() .'/storage/app/public/temp/'.$dir.'/'.$file_name);

        $data = [
            'attachment_id' => $dir,
            'attachmentresource_type' => 'job-invoice',
            'attachmentresource_id' => $serviceOrder->id,
            'attachment_directory' => $dir,
            'attachment_uniqiueid' => $dir,
            'attachment_filename' => $file_name,

        ];
        $this->attachmentRepo->clear($serviceOrder->id,'job-invoice');
        if(!$file = $this->attachmentRepo->process($data)){
            abort(409, 'Something went wrong');
        }
    }
}
