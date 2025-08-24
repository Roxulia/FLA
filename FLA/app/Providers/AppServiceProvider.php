<?php

namespace App\Providers;

use App\Models\LiveData;
use App\Repository\leagueRepo;
use App\Repository\leagueTableRepo;
use App\Repository\liveDataRepo;
use App\Repository\matchRepo;
use App\Repository\playerRepo;
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
        $this->app->singleton(liveDataRepo::class,function ($app){
            return new liveDataRepo();
        });
        $this->app->singleton(matchRepo::class,function ($app){
            return new matchRepo();
        });
        $this->app->singleton(playerRepo::class,function ($app){
            return new playerRepo();
        });
        $this->app->singleton(leagueTableRepo::class,function ($app){
            return new leagueTableRepo();
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
