<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgencyController extends Controller
{
    // ---------- PAGE SHOWN WHILE WAITING FOR ADMIN APPROVAL ----------
    public function pending()
    {
        return view('agency.pending');
    }

    // ---------- AGENCY DASHBOARD ----------
    public function dashboard()
    {
        // auth()->user()->agency is the relationship we defined in the User model
        $agency = auth()->user()->agency;

        $totalCars = $agency->cars()->count();

        // count bookings made for ANY of this agency's cars
        // whereHas lets us filter bookings based on a condition on the related "car"
        $totalBookings = Booking::whereHas('car', function ($q) use ($agency) {
            $q->where('agency_id', $agency->id);
        })->count();

        $pendingBookings = Booking::whereHas('car', function ($q) use ($agency) {
            $q->where('agency_id', $agency->id);
        })->where('status', 'pending')->count();

        return view('agency.dashboard', compact('agency', 'totalCars', 'totalBookings', 'pendingBookings'));
    }

    // ---------- LIST AGENCY'S CARS ----------
    public function carsIndex()
    {
        $cars = auth()->user()->agency->cars()->latest()->paginate(10);

        return view('agency.cars.index', compact('cars'));
    }

    // ---------- SHOW FORM TO ADD A CAR ----------
    public function carsCreate()
    {
        return view('agency.cars.create');
    }

    // ---------- SAVE NEW CAR ----------
    public function carsStore(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:'.(date('Y') + 1),
            'transmission' => 'required|in:manuelle,automatique',
            'fuel_type' => 'required|in:essence,diesel,electrique,hybride',
            'seats' => 'required|integer|min:2|max:9',
            'price_per_day' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048', // max 2MB, must be an image file
        ]);

        // handle the image upload if one was sent
        if ($request->hasFile('image')) {
            // store() saves the file inside storage/app/public/cars
            // and returns the path to save in the database
            $validated['image'] = $request->file('image')->store('cars', 'public');
        }

        // attach the car to the logged-in agency automatically
        $validated['agency_id'] = auth()->user()->agency->id;

        Car::create($validated);

        return redirect()->route('agency.cars.index')->with('success', 'Voiture ajoutée avec succès !');
    }

    // ---------- SHOW FORM TO EDIT A CAR ----------
    public function carsEdit(Car $car)
    {
        // SECURITY CHECK: make sure this car belongs to the logged-in agency
        $this->authorizeCar($car);

        return view('agency.cars.edit', compact('car'));
    }

    // ---------- UPDATE A CAR ----------
    public function carsUpdate(Request $request, Car $car)
    {
        $this->authorizeCar($car);

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:'.(date('Y') + 1),
            'transmission' => 'required|in:manuelle,automatique',
            'fuel_type' => 'required|in:essence,diesel,electrique,hybride',
            'seats' => 'required|integer|min:2|max:9',
            'price_per_day' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // delete the old image first if it exists, to save disk space
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $validated['image'] = $request->file('image')->store('cars', 'public');
        }

        // checkbox sends nothing when unchecked, so we force it to false if missing
        $validated['is_available'] = $request->has('is_available');

        $car->update($validated);

        return redirect()->route('agency.cars.index')->with('success', 'Voiture mise à jour avec succès !');
    }

    // ---------- DELETE A CAR ----------
    public function carsDestroy(Car $car)
    {
        $this->authorizeCar($car);

        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return back()->with('success', 'Voiture supprimée.');
    }

    // ---------- LIST BOOKINGS MADE ON AGENCY'S CARS ----------
    public function bookingsIndex()
    {
        $agency = auth()->user()->agency;

        $bookings = Booking::whereHas('car', function ($q) use ($agency) {
            $q->where('agency_id', $agency->id);
        })->with(['car', 'client'])->latest()->paginate(10);

        return view('agency.bookings.index', compact('bookings'));
    }

    // ---------- CONFIRM A BOOKING ----------
    public function bookingConfirm(Booking $booking)
    {
        $this->authorizeCar($booking->car);

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Réservation confirmée.');
    }

    // ---------- CANCEL A BOOKING (from agency side) ----------
    public function bookingCancel(Booking $booking)
    {
        $this->authorizeCar($booking->car);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Réservation annulée.');
    }

    // ---------- PRIVATE HELPER ----------
    // makes sure the given car actually belongs to the logged-in agency
    // this prevents agency A from editing/deleting agency B's cars by changing the URL
    private function authorizeCar(Car $car)
    {
        if ($car->agency_id !== auth()->user()->agency->id) {
            abort(403);
        }
    }
}
