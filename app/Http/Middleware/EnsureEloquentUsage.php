<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureEloquentUsage
{
    /**
     * Handle an incoming request and log any raw database queries for security review.
     * This middleware helps identify potential SQL injection vulnerabilities.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enable query logging in non-production to catch raw queries
        if (config('app.env') !== 'production') {
            DB::listen(function ($query) {
                // Log queries that might contain raw SQL
                $sql = strtolower($query->sql);
                
                // Check for potentially dangerous patterns
                if (
                    str_contains($sql, 'union') ||
                    str_contains($sql, 'drop table') ||
                    str_contains($sql, 'truncate') ||
                    preg_match('/;.*?(select|update|delete|insert)/i', $query->sql)
                ) {
                    Log::warning('Potentially dangerous SQL query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                        'url' => request()->fullUrl(),
                        'user_id' => auth()->id(),
                    ]);
                }
            });
        }

        return $next($request);
    }
}
