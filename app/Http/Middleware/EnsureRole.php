<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors(['auth' => 'Please login first.']);
        }

        $user = Auth::user();

        $allowedRoles = array_map('intval', $roles);

        if (!in_array((int) $user->role_id, $allowedRoles)) {
            return response()->view('errors.404', [], 404);
        }

        return $next($request);
    }
}
