<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAgencyApproved
{
    /**
     * This middleware makes sure an agency is "approved" before they can
     * manage cars. If they are still "pending" or got "rejected",
     * we redirect them to a waiting page instead.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // agency() is the relationship we defined in the User model
        $agency = $user->agency;

        if (!$agency || !$agency->isApproved()) {
            // redirect to a simple "please wait for approval" page
            return redirect()->route('agency.pending');
        }

        return $next($request);
    }
}
