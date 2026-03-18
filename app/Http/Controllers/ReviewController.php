<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // Get first 6 approved reviews for initial display
        $reviews = Feedback::with(['user', 'booking', 'booking.service'])
                    ->where('is_published', true)
                    ->orderByDesc('created_at')
                    ->take(6)
                    ->get();

        // Total approved reviews for Load More logic
        $totalReviews = Feedback::where('is_published', true)->count();

        // Average rating (optional)
        $averageRating = $totalReviews > 0
            ? round(Feedback::where('is_published', true)->avg('rating'), 1)
            : 0;

        // Pass all variables to the about page
        return view('about', compact('reviews', 'totalReviews', 'averageRating'));
    }

    // AJAX endpoint for Load More
    public function loadMore(Request $request)
    {
        $skip = $request->query('skip', 0);

        $moreReviews = Feedback::with(['user', 'booking', 'booking.service'])
                        ->where('is_published', true)
                        ->orderByDesc('created_at')
                        ->skip($skip)
                        ->take(6)
                        ->get();

        return response()->json($moreReviews);
    }
}