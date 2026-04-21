<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


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
    // app/Providers/AppServiceProvider.php
    public function boot(): void
    {


  Paginator::useBootstrap();





        // Super admin bypass - no role_has_permissions entries needed
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super admin') ? true : null;
        });

        view()->composer('*', function ($view) {
            // Default colors
            $defaultColors = [
                'p_color' => '#4477b6',
                'nh_color' => '#ffffff',
                'h_color' => '#ffffff',
                's_color' => '#ffffff',
            ];

            // Get application settings (logo, favicon, etc.)
            $appSettings = Setting::firstOrNew();

            // Initialize variables
            $colors = $defaultColors;
            $userSettings = null;

            // If user is authenticated, get their color settings
            if (auth()->check()) {
                $userSettings = auth()->user()->settings()->firstOrNew();
                $colors = [
                    'p_color' => $userSettings->p_color ?? $defaultColors['p_color'],
                    'nh_color' => $userSettings->nh_color ?? $defaultColors['nh_color'],
                    'h_color' => $userSettings->h_color ?? $defaultColors['h_color'],
                    's_color' => $userSettings->s_color ?? $defaultColors['s_color'],
                ];
            }

            // Share all data with every view
            $view->with(array_merge([
                'settings' => $userSettings, // User-specific settings
                'appSettings' => $appSettings, // Global application settings
                'webname' => $appSettings->webname ?? 'Default Name', // Website name
                'weblogo' => $appSettings->weblogo ? 'weblogo/' . $appSettings->weblogo : null, // Full logo path
                'favlogo' => $appSettings->favlogo ? 'favlogo/' . $appSettings->favlogo : null, // Full favicon path
            ], $colors));
        });



    }
}
