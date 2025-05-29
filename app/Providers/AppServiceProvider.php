<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;

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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['es','en','pt_BR'])
                ->circular()
                 ->flags([
                'es' => asset('storage/flags/es.png'),
                'en' => asset('storage/flags/us.png'),
                'pt_BR' => asset('storage/flags/br.png'),
            ]);
        });
    }
}
