<?php

namespace App\Providers;

use App\Services\LoginService;
use App\Services\ProxyOrderService;
use App\Services\UserService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LoginService::class);
        $this->app->singleton(UserService::class);
        $this->app->singleton(ProxyOrderService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootStrapFive();
    }
}
