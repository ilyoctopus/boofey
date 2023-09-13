<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = Cookie::get('potato');

        if (!$token) {
            $cookie = Cookie::forget('potato');

            return response()->json([
                'status' => 'error',
                'errors' => [
                    '401' => 'Access Denied: Please Log In to Access This Resource'
                ]
            ], 401)->withCookie($cookie);
        }

        // Verify the token and authenticate the user
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            $cookie = Cookie::forget('potato');

            return response()->json([
                'status' => 'error',
                'errors' => [
                    '401' => 'Access Denied: Please Log In to Access This Resource'
                ]
            ], 401)->withCookie($cookie);
        }

        // Check if the token has expired by comparing with the current time
        if ($accessToken->expires_at && now()->gt($accessToken->expires_at)) {
            // Token has expired
            $diffInHours = $accessToken->expires_at->diffInHours(now());

            if ($diffInHours <= 1) {
                // Within the last 1 hour, refresh the token
                // Set the expiration time based on the name of the old token
                $expiration = ($accessToken->name === 'long-lived-token') ? now()->addDays(3) : now()->addMinutes(15);
                $tokenName = ($accessToken->name === 'long-lived-token') ? 'long-lived-token' : 'short-lived-token';

                $newToken = $accessToken->tokenable->createToken($tokenName, ['*'], $expiration)->plainTextToken;
                // Revoke the last token
                $accessToken->delete();

                $accessToken = PersonalAccessToken::findToken($newToken);

                auth()->login($accessToken->tokenable);

                $response = $next($request);
                $cookie = Cookie::make('potato', $newToken); // Change the name, value, and expiration time as needed
                $response->withCookie($cookie);
        
                return $response;
            } else {
                $cookie = Cookie::forget('potato');

                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        '401' => 'Access Denied: Please Log In to Access This Resource'
                    ]
                ], 401)->withCookie($cookie);
            }
        }

        // Authenticate the user
        auth()->login($accessToken->tokenable);

        return $next($request);
    }
}
