<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // ---------- SHOW FORMS ----------

    // shows the login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // shows the registration page
    // the user picks "client" or "agency" when registering (not admin, admin is created manually)
    public function showRegister()
    {
        return view('auth.register');
    }

    // ---------- HANDLE REGISTER ----------

    public function register(Request $request)
    {
        // validate() automatically stops the request and shows errors
        // if any rule fails (Laravel handles this for us)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // must be unique in users table
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'role' => 'required|in:client,agency', // only these 2 values allowed from the form

            // these fields are only required if role = agency
            'agency_name' => 'required_if:role,agency|nullable|string|max:255',
            'agency_address' => 'required_if:role,agency|nullable|string|max:255',
            'agency_city' => 'required_if:role,agency|nullable|string|max:255',
        ]);

        // create the user account first
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // never store plain text password
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ]);

        // if they registered as an agency, also create the agency profile
        // status will default to "pending" (see migration), waiting for admin approval
        if ($validated['role'] === 'agency') {
            Agency::create([
                'user_id' => $user->id,
                'name' => $validated['agency_name'],
                'address' => $validated['agency_address'],
                'city' => $validated['agency_city'],
                'status' => 'pending',
            ]);
        }

        // log the user in right away
        Auth::login($user);

        // redirect based on role
        return $this->redirectByRole($user);
    }

    // ---------- HANDLE LOGIN ----------

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Auth::attempt checks the email/password against the database
        // it returns true if it matches, and automatically logs the user in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // security: prevents session fixation attacks

            return $this->redirectByRole(Auth::user());
        }

        // if login failed, go back to the form with an error message
        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    // ---------- LOGOUT ----------

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ---------- HELPER ----------

    // small private function used by both register() and login()
    // to send each role to their correct dashboard
    private function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isAgency()) {
            // if agency is not approved yet, the agency.approved middleware
            // will catch it and redirect to the pending page automatically
            return redirect()->route('agency.dashboard');
        }

        return redirect()->route('client.dashboard');
    }
}
