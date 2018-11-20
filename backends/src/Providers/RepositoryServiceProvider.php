<?php

namespace Huycds\Backends\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $driver = ucfirst(config('backends.driver'));

        if (strcasecmp($driver, 'custom') == 0) {
            $namespace = config('backends.custom_driver');
        } else {
            $namespace = 'Huycds\Backends\Repositories\\'.$driver.'Repository';
        }

        $this->app->bind('Huycds\Backends\Contracts\Repository', $namespace);
    }
}
