<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Upcoming Appointments
        $upcomingAppointments = Booking::with('service')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        // Past Appointments
        $pastAppointments = Booking::with('service')
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        // Total Visits
        $totalVisits = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Visits Today
        $visitsToday = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('appointment_date', $today)
            ->count();

        // Available Services Count
        $servicesCount = Service::where('status', 1)->count();

        // Available Services
        $availableServices = Service::where('status', 1)->get();

        return view('dashboard', [
            'user' => $user,
            'upcomingAppointments' => $upcomingAppointments,
            'pastAppointments' => $pastAppointments,
            'totalVisits' => $totalVisits,
            'visitsToday' => $visitsToday,
            'servicesCount' => $servicesCount,
            'availableServices' => $availableServices,
        ]);
    }
}