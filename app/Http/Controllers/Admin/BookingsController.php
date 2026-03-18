<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmedMail;
use App\Mail\BookingCompletedMail;
use Illuminate\Support\Facades\Mail;

class BookingsController extends Controller
{
    /**
     * DISPLAY BOOKINGS (SEARCH + FILTER + PAGINATION)
     */
    public function index(Request $request)
    {
        $query = Booking::query()->with('service');

        // SEARCH (NAME / EMAIL / PHONE)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // FILTER BOOKING STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FILTER PAYMENT STATUS
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * SHOW BOOKING DETAILS
     */
    public function show($id)
    {
        $booking = Booking::with('service')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * CONFIRM BOOKING
     */
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Send confirmation email
        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingConfirmedMail($booking));
        }

        return redirect()->back()
            ->with('success', 'Booking confirmed successfully.');
    }

    /**
     * COMPLETE BOOKING
     */
    public function complete($id)
    {
        $booking = Booking::with('service')->findOrFail($id);

        if ($booking->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Only confirmed bookings can be completed.');
        }

        $booking->update([
            'status' => 'completed',
            'payment_status' => 'paid',
            'completed_at' => now(),
            'total_price' => $booking->total_price ?? $booking->service->price
        ]);

        // Send completion email
        try {
            if ($booking->email) {
                Mail::to($booking->email)->send(new BookingCompletedMail($booking));
            }
        } catch (\Exception $e) {
            \Log::error("Booking Completion Email failed: " . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Booking marked as completed and email sent to the customer.');
    }

    /**
     * CANCEL BOOKING
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Completed bookings cannot be cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Booking cancelled successfully.');
    }
}