<?php 
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->logError($exception);
        }

        parent::report($exception);
    }

    protected function logError(Throwable $exception)
    {
        $user = auth()->user();
        
        Log::error('System Error', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user_id' => $user ? $user->id : 'Guest',
            'user_email' => $user ? $user->email : 'N/A',
            'url' => request()->fullUrl(),
            'ip' => request()->ip()
        ]);
    }

    public function render($request, Throwable $exception)
    {
        // Custom error pages
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->view('errors.403', [], 403);
        }

        if ($this->isHttpException($exception) && $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            switch ($exception->getStatusCode()) {
                case 404:
                    return response()->view('errors.404', [], 404);
                case 500:
                    return response()->view('errors.500', [], 500);
            }
        }

        return parent::render($request, $exception);
    }
}