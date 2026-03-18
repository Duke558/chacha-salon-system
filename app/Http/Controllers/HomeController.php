<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        $categories = ServiceCategory::where('status', true)->get()
            ->map(function ($category) {
                
                if (!$category->icon_class) {
                    $category->icon_class = match ($category->name) {
                        'Bridal Services' => 'fas fa-ring',
                        'Facial Services' => 'fas fa-spa',
                        'Hair Services' => 'fas fa-cut',
                        'Makeup Services' => 'fas fa-magic',
                        'Additional Services' => 'fas fa-star',
                        default => 'fas fa-scissors'
                    };
                }

                
                if (!$category->start_price) {
                    
                    $minPrice = $category->services()->min('price') ?? 0;
                    $category->start_price = $minPrice;
                }

                return $category;
            });

        return view('home', compact('categories'));
    }
}
