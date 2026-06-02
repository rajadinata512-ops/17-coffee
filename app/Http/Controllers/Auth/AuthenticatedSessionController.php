<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    private string $adminEmail = 'seventeencoffeee@gmail.com';

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        $request->session()->forget('otp_verified_' . $user->id);

        $sent = $this->sendOtpToEmail($user);

        return redirect()
            ->route('verification.notice')
            ->with(
                $sent ? 'success' : 'error',
                $sent
                    ? 'Kode OTP sudah dikirim ke Gmail kamu. Masukkan kode 6 digit untuk melanjutkan.'
                    : 'OTP gagal dikirim. Periksa setting MAIL di .env, lalu klik Kirim Ulang OTP.'
            );
    }

    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()) {
            $request->session()->forget('otp_verified_' . $request->user()->id);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectAfterOtp($user): RedirectResponse
    {
        if ($this->isAdminUser($user)) {
            return redirect('/admin/dashboard');
        }

        return redirect('/menu');
    }

    private function isAdminUser($user): bool
    {
        return strtolower((string) $user->email) === strtolower($this->adminEmail)
            || (isset($user->role) && strtolower((string) $user->role) === 'admin')
            || (isset($user->is_admin) && (bool) $user->is_admin);
    }

    private function sendOtpToEmail($user): bool
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_otp_hash' => Hash::make($otp),
            'email_otp_expires_at' => now()->addMinutes(10),
            'email_otp_attempts' => 0,
        ])->save();

        try {
            Mail::raw(
                "Halo {$user->name},\n\nKode OTP login/verifikasi akun 17 Coffee kamu adalah:\n\n{$otp}\n\nKode ini berlaku 10 menit.\nJangan berikan kode ini kepada siapa pun.\n\n17 Coffee",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Login 17 Coffee');
                }
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP 17 Coffee: ' . $e->getMessage());
            return false;
        }
    }
}