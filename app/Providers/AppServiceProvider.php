<?php

namespace App\Providers;

use App\Repositories\PaySlipRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\PresencesService;
use App\Repositories\PresencesRepository;
use App\Services\PaySlipService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PresencesService::class, function ($app) {
            return new PresencesService($app->make(PresencesRepository::class));
        });
        
        $this->app->singleton(PaySlipService::class, function ($app) {
            return new PaySlipService($app->make(PaySlipRepository::class), $app->make(PresencesRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
