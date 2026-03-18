<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\BookingCreatedMail;
use App\Mail\BookingNotificationMail;
use App\Mail\BookingCancelledMail;

class BookingController extends Controller
{
    
    public function index(Request $request)
    {
        $categories = ServiceCategory::where('status', true)->get();
        $selectedCategory = null;
        $selectedService = null;
        $services = Service::where('status', true)->get();

        if ($request->has('serviceCategory')) {
            $selectedCategory = ServiceCategory::find($request->serviceCategory);
            if ($selectedCategory) {
                $services = Service::where('category_id', $selectedCategory->id)
                    ->where('status', true)
                    ->get();
            }
        }

        if ($request->has('service')) {
            $selectedService = Service::find($request->service);
            if ($selectedService) {
                $selectedCategory = $selectedService->category;
                $services = Service::where('category_id', $selectedCategory->id)
                    ->where('status', true)
                    ->get();
            }
        }

        $timeSlots = $this->getTimeSlots();

        return view('booking', compact(
            'categories',
            'services',
            'timeSlots',
            'selectedService',
            'selectedCategory'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'serviceCategory' => 'required|exists:service_categories,id',
            'service' => 'required|exists:services,id',
            'appointmentDate' => 'required|date|after_or_equal:today',
            'appointmentTime' => 'required',
            'termsAccept' => 'required|accepted'
        ]);

        try {
            $user = Auth::user();
            $service = Service::findOrFail($request->service);
            $totalPrice = $service->price;

            $formattedTime = Carbon::createFromFormat(
                'h:i A',
                $request->appointmentTime
            )->format('H:i:s');

            $booking = Booking::create([
                'user_id' => $user ? $user->id : null,
                'full_name' => $request->fullName,
                'phone' => $request->phone,
                'email' => $request->email,
                'service_category_id' => $request->serviceCategory,
                'service_id' => $request->service,
                'appointment_date' => $request->appointmentDate,
                'appointment_time' => $formattedTime,
                'special_requirements' => $request->requirements,
                'base_price' => $totalPrice,
                'addons_price' => 0,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Customer Email
            if ($booking->email) {
                Mail::to($booking->email)
                    ->send(new BookingCreatedMail($booking));
            }

            // Admin Email
            Mail::to(config('mail.admin_email', config('mail.from.address')))
                ->send(new BookingNotificationMail($booking));

            return redirect()
                ->route('dashboard')
                ->with('success', 'Your booking has been received and is pending approval.');

        } catch (\Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    
    public function cancel(Request $request, $id)
    {
        try {
            $booking = Booking::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                $message = 'Only pending or confirmed bookings can be cancelled.';
                if ($request->ajax()) return response()->json(['message' => $message], 400);
                return redirect()->back()->with('error', $message);
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            // Send email to customer
            if ($booking->email) {
                Mail::to($booking->email)
                    ->send(new BookingCancelledMail($booking));
            }

            // Send email to admin
            $adminEmail = config('mail.admin_email', config('mail.from.address'));
            Mail::to($adminEmail)
                ->send(new BookingCancelledMail($booking, true));

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Booking Cancel Error: ' . $e->getMessage());
            $message = 'Failed to cancel booking. Please try again.';
            if ($request->ajax()) return response()->json(['message' => $message], 500);
            return redirect()->back()->with('error', $message);
        }
    }

   
    public function reschedule(Request $request, $id)
    {
        try {
            $booking = Booking::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($booking->status !== 'confirmed') {
                $message = 'Only confirmed bookings can be rescheduled.';
                if ($request->ajax()) return response()->json(['message' => $message], 400);
                return redirect()->back()->with('error', $message);
            }

            $newDate = Carbon::parse($booking->appointment_date)
                ->addDay()
                ->format('Y-m-d');

            $booking->update([
                'appointment_date' => $newDate
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Booking rescheduled successfully.');

        } catch (\Exception $e) {
            Log::error('Booking Reschedule Error: ' . $e->getMessage());
            $message = 'Failed to reschedule booking. Please try again.';
            if ($request->ajax()) return response()->json(['message' => $message], 500);
            return redirect()->back()->with('error', $message);
        }
    }

    
    private function getTimeSlots()
    {
        $slots = [];
        $start = Carbon::createFromTime(9, 0);
        $end = Carbon::createFromTime(20, 0);

        while ($start <= $end) {
            $slots[] = $start->format('h:i A');
            $start->addMinutes(30);
        }

        return $slots;
    }
}