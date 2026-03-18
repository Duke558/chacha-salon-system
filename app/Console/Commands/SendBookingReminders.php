<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingReminderMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';
    protected $description = 'Send appointment reminder emails 24 hours before';

    public function handle()
    {
        // Use Asia/Manila timezone
        $tomorrow = Carbon::tomorrow('Asia/Manila')->format('Y-m-d');

        $bookings = Booking::where('status', 'confirmed')
            ->where('appointment_date', $tomorrow)
            ->where('reminder_sent', false)
            ->get();

        if ($bookings->isEmpty()) {
            Log::info("No bookings to send reminders for $tomorrow");
            $this->info("No bookings to send reminders for $tomorrow");
            return Command::SUCCESS;
        }

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->email)->send(new BookingReminderMail($booking));

                $booking->update([
                    'reminder_sent' => true
                ]);

                Log::info("Reminder email sent to {$booking->email} for booking ID {$booking->id}");
                $this->info("Reminder email sent to {$booking->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send reminder to {$booking->email}: ".$e->getMessage());
                $this->error("Failed to send reminder to {$booking->email}");
            }
        }

        return Command::SUCCESS;
    }
}
