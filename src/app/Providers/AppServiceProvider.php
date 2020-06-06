<?php

namespace App\Providers;

use App\Services\IProductService;
use App\Services\ProductService;
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
        $this->injectServices();
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

    private function injectServices()
    {
        $this->app->bind(IProductService::class, ProductService::class);
    }
}
