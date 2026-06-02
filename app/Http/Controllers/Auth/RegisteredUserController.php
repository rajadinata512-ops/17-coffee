<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private string $adminEmail = 'seventeencoffeee@gmail.com';

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = strtolower($request->email);
        $isAdminEmail = $email === strtolower($this->adminEmail);

        $user = new User();
        $user->name = $request->name;
        $user->email = $email;
        $user->password = Hash::make($request->password);

        if (Schema::hasColumn('users', 'role')) {
            $user->role = $isAdminEmail ? 'admin' : 'user';
        }

        if (Schema::hasColumn('users', 'is_admin')) {
            $user->is_admin = $isAdminEmail;
        }

        $user->email_verified_at = null;
        $user->save();

        event(new Registered($user));
        Auth::login($user);

        $request->session()->forget('otp_verified_' . $user->id);

        $sent = $this->sendOtpToEmail($user);

        return redirect()
            ->route('verification.notice')
            ->with(
                $sent ? 'success' : 'error',
                $sent
                    ? 'Kode OTP sudah dikirim ke Gmail kamu. Masukkan kode 6 digit untuk mengaktifkan akun.'
                    : 'Akun dibuat, tapi OTP gagal dikirim. Periksa setting MAIL di .env lalu klik Kirim Ulang OTP.'
            );
    }

    private function sendOtpToEmail(User $user): bool
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_otp_hash' => Hash::make($otp),
            'email_otp_expires_at' => now()->addMinutes(10),
            'email_otp_attempts' => 0,
        ])->save();

        try {
            Mail::raw(
                "Halo {$user->name},\n\nKode OTP pendaftaran akun 17 Coffee kamu adalah:\n\n{$otp}\n\nKode ini berlaku 10 menit.\nJangan berikan kode ini kepada siapa pun.\n\n17 Coffee",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Pendaftaran 17 Coffee');
                }
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP 17 Coffee: ' . $e->getMessage());
            return false;
        }
    }
}