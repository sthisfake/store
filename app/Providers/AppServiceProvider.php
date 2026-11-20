<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SessionCart;
use Illuminate\Support\Facades\View;

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
        View::composer(
            'store.partials.header',
            function ($view): void {
                $view->with(
                    'headerCart',
                    app(SessionCart::class)->summary(),
                );
            },
        );
    }
}
