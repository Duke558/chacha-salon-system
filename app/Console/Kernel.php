<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Register your custom command
        \App\Console\Commands\SendBookingReminders::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run the reminder email command daily at 9 AM Manila time
        $schedule->command('bookings:send-reminders')
                 ->dailyAt('09:00')
                 ->timezone('Asia/Manila')
                 ->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}