<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    private string $adminEmail = 'seventeencoffeee@gmail.com';

    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if (strtolower((string) $user->email) !== strtolower($this->adminEmail)) {
            abort(403, 'Akses admin hanya untuk akun resmi 17 Coffee.');
        }

        if (($user->role ?? null) !== 'admin' || !($user->is_admin ?? false)) {
            $user->forceFill([
                'role' => 'admin',
                'is_admin' => true,
            ])->save();
        }

        return $next($request);
    }
}