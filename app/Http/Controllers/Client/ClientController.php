<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // ---------- CLIENT DASHBOARD ----------
    public function dashboard()
    {
        // auth()->id() gives us the currently logged-in user's id
        $clientId = auth()->id();

        // get a few stats to show on the dashboard
        $totalBookings = Booking::where('client_id', $clientId)->count();
        $activeBookings = Booking::where('client_id', $clientId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // last 5 bookings to show as a quick preview
        $recentBookings = Booking::where('client_id', $clientId)
            ->with('car') // eager load the car relationship
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact('totalBookings', 'activeBookings', 'recentBookings'));
    }

    // ---------- LIST ALL MY BOOKINGS ----------
    public function bookingsIndex()
    {
        $bookings = Booking::where('client_id', auth()->id())
            ->with('car.agency') // eager load car AND the car's agency
            ->latest()
            ->paginate(10);

        return view('client.bookings.index', compact('bookings'));
    }

    // ---------- CREATE A NEW BOOKING ----------
    public function storeBooking(Request $request, Car $car)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // calculate number of days between start and end date
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $days = $start->diffInDays($end) + 1; // +1 because both start and end day count

        // calculate the total price automatically
        $totalPrice = $days * $car->price_per_day;

        Booking::create([
            'client_id' => auth()->id(),
            'car_id' => $car->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
            'status' => 'pending', // waits for confirmation
        ]);

        return redirect()->route('client.bookings.index')
            ->with('success', 'Votre réservation a été envoyée avec succès !');
    }

    // ---------- CANCEL A BOOKING ----------
    public function cancelBooking(Booking $booking)
    {
        // SECURITY CHECK: make sure this booking belongs to the logged-in client
        // otherwise someone could cancel another client's booking just by changing the URL id
        if ($booking->client_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Réservation annulée.');
    }

    // ---------- LEAVE A REVIEW ----------
    public function storeReview(Request $request, Car $car)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'client_id' => auth()->id(),
            'car_id' => $car->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }
}
