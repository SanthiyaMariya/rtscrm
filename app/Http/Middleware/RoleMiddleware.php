<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

            // 2. ADD THIS: Check if the account is deactivated (status 0)
        // This will kick them out instantly if the admin toggles them off
        if (Auth::user()->status == 0) {
            Auth::logout(); // Force logout
            return redirect('/')->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        

        $userRole = Auth::user()->designation; // Using designation to define roles

        if ($role == 'admin' && $userRole == 'Project Manager') {
            return $next($request);
        }

        if ($role == 'employee' && $userRole != 'Project Manager') {
            return $next($request);
        }

        return redirect('/'); // Unauthorized
    }

    // Inside handle()

}