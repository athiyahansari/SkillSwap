<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        \App\Models\Booking::observe(\App\Observers\BookingObserver::class);
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\AuthEventSubscriber::class);
    }
}
