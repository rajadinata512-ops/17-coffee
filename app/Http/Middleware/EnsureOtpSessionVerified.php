<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpSessionVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $otpSessionKey = 'otp_verified_' . $user->id;

        if ($request->session()->get($otpSessionKey) === true) {
            return $next($request);
        }

        return redirect()
            ->route('verification.notice')
            ->with('error', 'Masukkan kode OTP dari Gmail terlebih dahulu sebelum masuk.');
    }
}