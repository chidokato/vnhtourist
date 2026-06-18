<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('hasDisplayValue', function ($value): bool {
            if ($value instanceof ViewErrorBag) {
                return true;
            }

            if ($value instanceof Collection) {
                return $value->isNotEmpty();
            }

            if (is_array($value)) {
                return $value !== [];
            }

            if (is_string($value)) {
                return trim(strip_tags($value)) !== '';
            }

            return $value !== null;
        });

        View::share('displayValue', function ($value, string $fallback = '...') {
            $hasDisplayValue = app('view')->shared('hasDisplayValue');

            return $hasDisplayValue($value) ? $value : $fallback;
        });
    }
}
