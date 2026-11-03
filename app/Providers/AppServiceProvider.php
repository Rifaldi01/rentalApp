<?php

namespace App\Providers;

use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            $rental = 0;

            if (Auth::check()) { // Pastikan pengguna sudah login
                $rental = Rental::where('status', '3')
                    ->count();
            }

            $view->with('rental', $rental);
        });
    }
}
