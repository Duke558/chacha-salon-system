<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;
use App\Mail\BookingRejectedMail;
use App\Mail\BookingCancelledMail;
use App\Mail\BookingCompletedMail;
use Carbon\Carbon;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /*  DASHBOARD  */
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->setTimezone('Asia/Manila')->toDateString());

        // Total bookings for the selected date
        $todayBookings = Booking::whereDate('appointment_date', $selectedDate)->count();

        // Total revenue for the selected date (only completed & paid bookings)
        $totalRevenue = Booking::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereDate('appointment_date', $selectedDate)
            ->sum('total_price');

        // Total customers for the selected date (unique emails from bookings created that day)
        $totalCustomers = Booking::whereDate('created_at', $selectedDate)
            ->distinct('email')
            ->count('email');

        $totalServices = Service::count();

        // Recent bookings for selected date
        $recentBookings = Booking::with(['service', 'category'])
            ->when($request->filled('date'), function($query) use ($selectedDate) {
                $query->whereDate('appointment_date', $selectedDate);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Popular services based on bookings for selected date
        $popularServices = Service::with('category')
            ->leftJoin('bookings', function($join) use ($selectedDate){
                $join->on('services.id','=','bookings.service_id')
                     ->where('bookings.status','completed')
                     ->where('bookings.payment_status','paid')
                     ->whereDate('bookings.appointment_date', $selectedDate);
            })
            ->groupBy(
                'services.id',
                'services.name',
                'services.description',
                'services.features',
                'services.duration',
                'services.price',
                'services.category_id',
                'services.status',
                'services.created_at',
                'services.updated_at'
            )
            ->select('services.*')
            ->selectRaw('COUNT(bookings.id) as bookings_count')
            ->selectRaw('COALESCE(SUM(bookings.total_price),0) as revenue')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'todayBookings',
            'totalRevenue',
            'totalServices',
            'totalCustomers',
            'recentBookings',
            'popularServices',
            'selectedDate'
        ));
    }

    /*  SERVICES */
    public function services()
    {
        $services = Service::with('category')
            ->withCount('bookings')
            ->orderBy('name')
            ->get();

        return view('admin.services.index', compact('services'));
    }

    public function createService()
    {
        $categories = ServiceCategory::all();
        return view('admin.services.create', compact('categories'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'features'    => 'nullable|array',
            'hours'       => 'nullable|integer|min:0',
            'minutes'     => 'nullable|integer|min:0|max:59',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:service_categories,id',
            'status'      => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $totalDuration = ($request->hours ?? 0) * 60 + ($request->minutes ?? 0);

        if ($totalDuration < 1) {
            return back()
                ->withErrors(['duration' => 'Please enter a valid service duration'])
                ->withInput();
        }

        $service = Service::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'features'    => $validated['features'] ?? [],
            'duration'    => $totalDuration,
            'price'       => $validated['price'],
            'category_id' => $validated['category_id'],
            'status'      => $request->has('status'),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            ServiceImage::create([
                'service_id' => $service->id,
                'image_path' => $path,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.services')
            ->with('success', 'Service created successfully.');
    }

    public function editService(Service $service)
    {
        $categories = ServiceCategory::where('status', true)->get();
        $hours = intdiv($service->duration, 60);
        $minutes = $service->duration % 60;

        return view('admin.services.edit', compact(
            'service',
            'categories',
            'hours',
            'minutes'
        ));
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'features'    => 'nullable|array',
            'hours'       => 'required|integer|min:0',
            'minutes'     => 'required|integer|min:0|max:59',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:service_categories,id',
            'status'      => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $totalDuration = ($request->hours * 60) + $request->minutes;

        if ($totalDuration < 1) {
            return back()
                ->withErrors(['duration' => 'Please enter a valid service duration'])
                ->withInput();
        }

        $service->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'features'    => $validated['features'] ?? [],
            'duration'    => $totalDuration,
            'price'       => $validated['price'],
            'category_id' => $validated['category_id'],
            'status'      => $request->has('status'),
        ]);

        if ($request->hasFile('image')) {
            $oldImage = $service->primaryImage;
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            $path = $request->file('image')->store('services', 'public');
            ServiceImage::create([
                'service_id' => $service->id,
                'image_path' => $path,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.services')
            ->with('success', 'Service updated successfully.');
    }

    public function destroyService(Service $service)
    {
        // Delete related bookings
        $service->bookings()->delete();

        // Delete primary image if exists
        $primaryImage = $service->primaryImage;
        if ($primaryImage) {
            Storage::disk('public')->delete($primaryImage->image_path);
            $primaryImage->delete();
        }

        // Delete the service itself
        $service->delete();

        return redirect()->route('admin.services')
            ->with('success', 'Service and its bookings deleted successfully.');
    }

    /*  BOOKINGS */
    public function bookings(Request $request)
    {
        $query = Booking::with(['service', 'category']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function confirmBooking(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingConfirmedMail($booking));
        }

        return back()->with('success', 'Booking confirmed.');
    }

    public function rejectBooking(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingRejectedMail($booking));
        }

        return back()->with('success', 'Booking rejected.');
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingCancelledMail($booking));
        }

        return back()->with('success', 'Booking cancelled.');
    }

    public function completeBooking(Booking $booking)
    {
        $booking->update([
            'status' => 'completed',
            'payment_status' => 'paid',
            'completed_at' => now(),
        ]);

        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingCompletedMail($booking));
        }

        return back()->with('success', 'Booking completed.');
    }

    /*  USERS */
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}