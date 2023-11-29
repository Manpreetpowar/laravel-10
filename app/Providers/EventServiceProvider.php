<?php

namespace App\Providers;

use App\Events\GenerateInvoiceEvent;
use App\Listeners\GenerateInvoice;
use App\Events\GenerateMonthlyStatementInvoiceEvent;
use App\Listeners\GenerateMonthlyStatementInvoice;
use App\Events\GenerateCreditNoteInvoiceEvent;
use App\Listeners\GenerateCreditNoteInvoice;
use App\Events\GenerateAdHocServiceEvent;
use App\Listeners\GenerateAdHocService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        GenerateInvoiceEvent::class => [
            GenerateInvoice::class,
        ],
        GenerateMonthlyStatementInvoiceEvent::class => [
            GenerateMonthlyStatementInvoice::class,
        ],
        GenerateCreditNoteInvoiceEvent::class => [
            GenerateCreditNoteInvoice::class,
        ],
        GenerateAdHocServiceEvent::class => [
            GenerateAdHocService::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
