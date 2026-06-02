<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    if (class_exists(RegisteredUserController::class)) {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);
    }

    if (class_exists(AuthenticatedSessionController::class)) {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    }

    if (class_exists(PasswordResetLinkController::class)) {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    }

    if (class_exists(NewPasswordController::class)) {
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    }
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('verify-email', function (Request $request) {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus 6 angka.',
        ]);

        $user = $request->user();

        if (!$user->email_otp_hash || !$user->email_otp_expires_at) {
            return back()->with('error', 'Kode OTP belum dibuat. Klik Kirim Ulang OTP.');
        }

        if ((int) $user->email_otp_attempts >= 5) {
            return back()->with('error', 'Terlalu banyak percobaan salah. Klik Kirim Ulang OTP untuk membuat kode baru.');
        }

        if (now()->greaterThan($user->email_otp_expires_at)) {
            return back()->with('error', 'Kode OTP sudah kedaluwarsa. Klik Kirim Ulang OTP.');
        }

        if (!Hash::check((string) $request->otp, (string) $user->email_otp_hash)) {
            $user->forceFill([
                'email_otp_attempts' => ((int) $user->email_otp_attempts) + 1,
            ])->save();

            return back()->with('error', 'Kode OTP salah. Cek Gmail kamu dan coba lagi.');
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_otp_hash' => null,
            'email_otp_expires_at' => null,
            'email_otp_attempts' => 0,
        ])->save();

        $request->session()->put('otp_verified_' . $user->id, true);

        if (
            strtolower((string) $user->email) === 'seventeencoffeee@gmail.com'
            || (isset($user->role) && strtolower((string) $user->role) === 'admin')
            || (isset($user->is_admin) && (bool) $user->is_admin)
        ) {
            return redirect('/admin/dashboard')->with('success', 'OTP benar. Selamat datang admin.');
        }

        return redirect('/menu')->with('success', 'OTP benar. Selamat datang.');
    })->middleware('throttle:6,1')->name('verification.otp');

    Route::post('email/verification-notification', function (Request $request) {
        $user = $request->user();

        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_otp_hash' => Hash::make($otp),
            'email_otp_expires_at' => now()->addMinutes(10),
            'email_otp_attempts' => 0,
        ])->save();

        $request->session()->forget('otp_verified_' . $user->id);

        try {
            Mail::raw(
                "Halo {$user->name},\n\nKode OTP login/verifikasi akun 17 Coffee kamu adalah:\n\n{$otp}\n\nKode ini berlaku 10 menit.\nJangan berikan kode ini kepada siapa pun.\n\n17 Coffee",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Login 17 Coffee');
                }
            );

            return back()->with('success', 'Kode OTP baru sudah dikirim ke Gmail kamu.');
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang OTP 17 Coffee: ' . $e->getMessage());

            return back()->with('error', 'OTP gagal dikirim. Periksa MAIL di .env, lalu coba lagi.');
        }
    })->middleware('throttle:6,1')->name('verification.send');

    if (class_exists(ConfirmablePasswordController::class)) {
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    }

    if (class_exists(PasswordController::class)) {
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    }

    if (class_exists(AuthenticatedSessionController::class)) {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    }
});