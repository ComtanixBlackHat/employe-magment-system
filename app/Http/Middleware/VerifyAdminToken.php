<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class VerifyAdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        // Check if the request has an access token in the Authorization header
        if ($request->header('Authorization')) {
            $accessToken = str_replace('Bearer ', '', $request->header('Authorization'));

            // Retrieve the user based on the access token
            $user = User::where('remember_token', $accessToken)->first();

            // If a user is found, set the authenticated user
            if ($user) {
                Auth::login($user);

                // Check if the user role is admin
                if ($user->role === 'admin') {
                    return $next($request);
                } else {
                    // Return 401 Unauthorized if the user role is not admin
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            }
        }

        // Return 401 Unauthorized if no access token is provided
        return response()->json(['error' => 'Unauthorized'], 401);

    }


}
