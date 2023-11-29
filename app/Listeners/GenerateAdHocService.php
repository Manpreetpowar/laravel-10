<?php

namespace App\Listeners;

use App\Models\AdHocService;
use App\Events\GenerateAdHocServiceEvent;
use App\Repositories\MachineRepository;
use App\Repositories\AttachmentRepository;
use App\Repositories\AdHocServiceRepository;
use App\Mail\AdHocServiceReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Log, PDF, PDFMerger;

class GenerateAdHocService
{

    protected $machineRepo;
    protected $attachmentRepo;
    protected $adHocServiceRepo;

    /**
     * Create the event listener.
     */
    public function __construct(MachineRepository $machineRepo, AttachmentRepository $attachmentRepo, AdHocServiceRepository $adHocServiceRepo)
    {
        $this->machineRepo = $machineRepo;
        $this->attachmentRepo = $attachmentRepo;
        $this->adHocServiceRepo = $adHocServiceRepo;
    }

    /**
     * Handle the event.
     */
    public function handle(GenerateAdHocServiceEvent $event): void
    {
        $machine_id = $event->machine_id;

        $machine = $this->machineRepo->get($machine_id)->first();

        if($machine->mileage_servicing_reminder < $machine->current_mileage){

            $service_id = generateUniqueID(new AdHocService, 4);
            request()->merge(['service_id' => $service_id, 'machine_id' => $machine->id, 'reminder_date'=> Carbon::now()]);

            $service = $this->adHocServiceRepo->create();

            request()->merge(['current_mileage'=>0]);
            $this->machineRepo->update($machine->id);

            // send invoice to client
            Mail::to(settings('settings_service_notification_email'))->send(new AdHocServiceReminder($machine));
        }

    }
}
