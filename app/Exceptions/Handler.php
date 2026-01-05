<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log sensitive exceptions with full context
            if (!($e instanceof ValidationException) && !($e instanceof AuthenticationException)) {
                Log::error('Exception occurred', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : 'Hidden in production',
                    'url' => request()->fullUrl(),
                    'user_id' => auth()->id(),
                    'ip' => request()->ip(),
                ]);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // In production (APP_DEBUG=false), show generic error pages without sensitive details
        if (!config('app.debug')) {
            // Handle 404 errors
            if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                return response()->view('errors.404', [], 404);
            }

            // Handle 403 errors
            if ($e instanceof HttpException && $e->getStatusCode() === 403) {
                return response()->view('errors.403', [], 403);
            }

            // Handle 401/authentication errors
            if ($e instanceof AuthenticationException) {
                return $request->expectsJson()
                    ? response()->json(['message' => 'Unauthenticated.'], 401)
                    : redirect()->guest(route('login'));
            }

            // Handle 500 and other server errors with generic message
            if ($e instanceof HttpException && $e->getStatusCode() >= 500) {
                return response()->view('errors.500', [], $e->getStatusCode());
            }

            // Catch-all for any other exceptions - show generic 500 error
            if (!($e instanceof ValidationException)) {
                return response()->view('errors.500', [], 500);
            }
        }

        return parent::render($request, $e);
    }
}
