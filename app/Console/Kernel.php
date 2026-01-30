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
        // Delete sent copies older than 7 days daily at 2 AM
        $schedule->command('copies:delete-old')->dailyAt('02:00');

        // Send subscription ending notifications
        // Notify customers 7 days before subscription ends - runs daily at 9 AM
        $schedule->command('subscriptions:notify-7days')->dailyAt('09:00');
        
        // Notify customers 3 days before subscription ends - runs daily at 9 AM
        $schedule->command('subscriptions:notify-3days')->dailyAt('09:00');
        
        // Notify customers on the day subscription ends - runs daily at 9 AM
        $schedule->command('subscriptions:notify-today')->dailyAt('09:00');
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
