<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('stats:collect-response')->quarterly()->at('2:00');

        $schedule->command('survey:send-emails')->quarterly()->at('3:00');

        $schedule->call(function () {
            app(\App\Services\SurveyResultService::class)->aggregateMonthlyResults();
        })->monthlyOn(1, '3:00');

        $schedule->command('notify:task-deadlines')->everyMinute();
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
