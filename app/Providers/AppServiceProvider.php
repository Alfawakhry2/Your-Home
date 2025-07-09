<?php

namespace App\Providers;

use App\Services\PaymobService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymobService::class, function ($app) {
            return new PaymobService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (app()->environment('local')) {
        //     \URL::forceScheme('https');
        // }
    }
}
