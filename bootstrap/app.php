<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        /**
         * Validasi gagal: catat ke riwayat pesan agar dapat diaudit,
         * lalu kembalikan pengguna ke form dengan old input.
         */
        $exceptions->render(function (ValidationException $exception, Request $request) {
            MessageService::catatValidasiGagal(
                $exception->errors(),
                $request->fullUrl(),
                $request->isMethod('get') ? null : $request->headers->get('referer'),
            );

            return null;
        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            MessageService::flash(
                Message::TYPE_WARNING,
                'Akses ditolak',
                'Silakan masuk terlebih dahulu untuk membuka halaman tersebut.',
            );

            return redirect()->guest(route('login'));
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            return response()->view('errors.403', [
                'pesan' => $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk halaman ini.',
            ], 403);
        });

        $exceptions->render(function (NotFoundHttpException|ModelNotFoundException $exception, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            return response()->view('errors.404', [
                'pesan' => 'Halaman atau data yang Anda cari tidak ditemukan.',
            ], 404);
        });

        $exceptions->render(function (Throwable $exception, Request $request) {
            if ($request->expectsJson() || config('app.debug')) {
                return null;
            }

            if ($exception instanceof ValidationException) {
                return null;
            }

            return response()->view('errors.500', [
                'pesan' => 'Terjadi kesalahan pada sistem. Tim kami sudah menerima laporan ini.',
            ], 500);
        });
    })->create();
