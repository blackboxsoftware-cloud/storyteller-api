<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // return $request->expectsJson() ? null : route('login');
        // Check if the request expects a JSON response (for API requests)
        if ($request->expectsJson()) {
            // Return a 401 Unauthorized response for API requests
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // For non-API requests (web), we don't need to redirect to the login page.
        return null;
    }
}
