<?php

namespace App\Console\Commands;

use App\Repositories\MachineRepository;
use App\Mail\AdHocServiceReminder as ServiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Repositories\AdHocServiceRepository;

class AdHocServiceReminder extends Command
{
    protected $machineRepo;
    protected $serviceRepo;
    public function __construct(
        MachineRepository       $machineRepo,
        AdHocServiceRepository  $serviceRepo)
    {
        $this->machineRepo  = $machineRepo;
        $this->serviceRepo  = $serviceRepo;
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ad-hoc-service-reminder';

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
        // send invoice to client
        $reminderMachine = $this->serviceRepo->get()
        ->whereNotNull('reminder_date')
        ->whereNull('service_date')
        ->get();
        foreach ($reminderMachine as $service) {
        Mail::to(settings('settings_service_notification_email'))->send(new ServiceMail($service->machine));
        }
    }
}
