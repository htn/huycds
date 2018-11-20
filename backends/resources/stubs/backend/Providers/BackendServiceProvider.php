<?php

namespace DummyNamespace\Providers;

use Huycds\Backends\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the backend services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'DummySlug');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'DummySlug');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'DummySlug');
        $this->loadConfigsFrom(__DIR__.'/../config');
        $this->loadFactoriesFrom(__DIR__.'/../Database/Factories');
    }

    /**
     * Register the backend services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
