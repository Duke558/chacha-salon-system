<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $isAdmin;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     * @param bool $isAdmin
     */
    public function __construct(Booking $booking, $isAdmin = false)
    {
        $this->booking = $booking;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isAdmin
            ? 'Booking Cancelled by Customer'
            : 'Your Booking Has Been Cancelled';

        return $this->markdown('emails.bookings.cancelled')
                    ->subject($subject)
                    ->with([
                        'booking' => $this->booking,
                        'isAdmin' => $this->isAdmin
                    ]);
    }
}