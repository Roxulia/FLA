<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiFetcher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiFetcher::class, function ($app) {
            $config = config('services.football_api');
            return new ApiFetcher(
                $config['base_url'],
                $config['key'],
                $config['host']
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
