<?php

namespace App\Providers;

use App\Services\OrderExpiryService;
use App\Observers\ProductObserver;
use App\Models\Product;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->runningInConsole()) {
            try {
                app(OrderExpiryService::class)->expire();
            } catch (\Throwable $e) {
                //
            }
        }

        Product::observe(ProductObserver::class);
        // Rate limiting
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by(
                optional($request->user())->id ?: $request->ip()
            );
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}