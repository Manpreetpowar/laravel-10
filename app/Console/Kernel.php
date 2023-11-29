<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

   /**
    * The Artisan commands provided by your application.
    *
    * @var array
    */
    protected $commands = [
        Commands\AdHocServiceReminder::class,
        Commands\GenerateStatementOfAccount::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:generate-statement-of-account')->everyFiveSeconds();
        // $schedule->command('app:generate-statement-of-account')->monthlyOn(1, '09:00');
        // $schedule->command('app:ad-hoc-service-reminder')->mondays()->at('09:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
