<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (! Auth::check()) {
            abort(401, 'Unauthorized. You must be logged in.');
        }

        $user = Auth::user();

        // تحقق من وجود الدور
        if (! $user->role) {
            abort(403, 'Forbidden. User does not have a role assigned.');
        }

        // تحقق من الصلاحية
        if (! $user->hasPermission($permission)) {
            abort(403, 'Forbidden. You do not have the required permission: '.$permission);
        }

        return $next($request);
    }
}
