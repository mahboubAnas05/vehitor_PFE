<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * This middleware blocks access if the user's role doesn't match.
     *
     * Usage in routes: ->middleware('role:admin')
     * or for multiple roles: ->middleware('role:admin,agency')
     *
     * $role here will receive the role(s) we wrote after "role:" in the route file
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // if nobody is logged in, send them to login page
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // auth()->user()->role is the role column we added on the users table
        // in_array checks if the current user's role is inside the allowed roles list
        if (!in_array(auth()->user()->role, $roles)) {
            // 403 = "Forbidden", user is logged in but not allowed here
            abort(403, 'Vous n\'avez pas accès à cette page.');
        }

        // role is correct, let the request continue
        return $next($request);
    }
}
