<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Enhanced API rate limiting with tiered limits
        RateLimiter::for('api', function (Request $request) {
            // Authenticated users: 100 requests per minute per user
            // Unauthenticated: 30 requests per minute per IP
            return $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)->response(function () {
                    return response()->json(['message' => 'Too many requests. Please slow down.'], 429);
                })
                : Limit::perMinute(30)->by($request->ip())->response(function () {
                    return response()->json(['message' => 'Too many requests. Please slow down.'], 429);
                });
        });

        // Strict rate limiting for sensitive write operations
        RateLimiter::for('api-writes', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(50)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });

        // Login attempts - strict limiting
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by(
                strtolower((string) $request->input('email')).'|'.$request->ip()
            );
        });

        // Password reset - very strict limiting
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(5)->by(
                strtolower((string) $request->input('email')).'|'.$request->ip()
            );
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
