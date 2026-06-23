<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ---------- ADMIN DASHBOARD ----------
    public function dashboard()
    {
        // simple counts for the stat boxes
        $totalAgencies = Agency::count();
        $pendingAgencies = Agency::where('status', 'pending')->count();
        $totalClients = User::where('role', 'client')->count();
        $totalCars = Car::count();
        $totalBookings = Booking::count();

        // sum of total_price for all confirmed/completed bookings = total revenue
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        // last 5 agencies waiting for approval, shown as a quick to-do list
        $recentPendingAgencies = Agency::where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAgencies',
            'pendingAgencies',
            'totalClients',
            'totalCars',
            'totalBookings',
            'totalRevenue',
            'recentPendingAgencies'
        ));
    }

    // ---------- AGENCIES: LIST ALL ----------
    public function agenciesIndex()
    {
        $agencies = Agency::with('user')->latest()->paginate(10);

        return view('admin.agencies.index', compact('agencies'));
    }

    // ---------- AGENCIES: APPROVE ----------
    public function agencyApprove(Agency $agency)
    {
        $agency->update(['status' => 'approved']);

        return back()->with('success', 'Agence approuvée avec succès.');
    }

    // ---------- AGENCIES: REJECT ----------
    public function agencyReject(Agency $agency)
    {
        $agency->update(['status' => 'rejected']);

        return back()->with('success', 'Agence rejetée.');
    }

    // ---------- USERS: LIST ALL (clients + agencies, not admins) ----------
    public function usersIndex()
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // ---------- USERS: DELETE ----------
    public function userDestroy(User $user)
    {
        // safety check: never allow deleting an admin account this way
        if ($user->isAdmin()) {
            abort(403);
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    // ---------- BOOKINGS: LIST ALL ----------
    public function bookingsIndex()
    {
        $bookings = Booking::with(['client', 'car.agency'])->latest()->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }
}
