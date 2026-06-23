<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // ---------- HOMEPAGE ----------
    public function home()
    {
        // show 6 latest available cars on the homepage as a preview
        $cars = Car::where('is_available', true)
            ->whereHas('agency', function ($query) {
                $query->where('status', 'approved'); // only show cars from approved agencies
            })
            ->latest() // order by newest first
            ->take(6)
            ->get();

        return view('home', compact('cars'));
    }

    // ---------- CAR LIST (with search/filter) ----------
    public function carsIndex(Request $request)
    {
        // start the query: only available cars from approved agencies
        $query = Car::where('is_available', true)
            ->whereHas('agency', function ($q) {
                $q->where('status', 'approved');
            });

        // if user searched by brand/model (the "search" input from the form)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // if user filtered by city (we look at the related agency's city)
        if ($request->filled('city')) {
            $query->whereHas('agency', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // if user filtered by transmission (manual/automatic)
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        // paginate() automatically splits results into pages of 9
        $cars = $query->latest()->paginate(9)->withQueryString();

        return view('cars.index', compact('cars'));
    }

    // ---------- SHOW ONE CAR DETAIL ----------
    public function carShow(Car $car)
    {
        // load the car's agency and reviews (with the client who wrote them) all at once
        // this avoids running extra queries later in the view ("eager loading")
        $car->load(['agency', 'reviews.client']);

        return view('cars.show', compact('car'));
    }
}
