<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Field yang tidak di-flash ke session saat validasi gagal.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return redirect()->back()
                ->withInput($request->except([
                    'password',
                    'password_baru',
                    'password_baru_confirmation',
                    'password_lama',
                    '_token',
                ]))
                ->with('error', 'Sesi Anda sudah berakhir karena terlalu lama tidak aktif. Halaman sudah diperbarui, silakan coba lagi.');
        }

        return parent::render($request, $e);
    }
}
