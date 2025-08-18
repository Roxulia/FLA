<?php

namespace App\Providers;

use App\Models\LiveData;
use App\Repository\leagueRepo;
use App\Repository\teamRepo;
use Illuminate\Support\ServiceProvider;
use App\Services\ApiFetcher;
use App\Services\LiveDataFetcher;

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

        $this->app->singleton(LiveDataFetcher::class, function ($app) {
            $config = config('services.live_api');
            return new LiveDataFetcher(
                $config['base_url'],
                $config['key'],
                $config['host']
            );
        });

        $this->app->singleton(leagueRepo::class,function ($app){
            return new leagueRepo();
        });

        $this->app->singleton(teamRepo::class,function ($app){
            return new teamRepo();
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
