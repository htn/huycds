<?php

namespace Huycds\Backends;

use Huycds\Backends\Contracts\Repository;
use Huycds\Backends\Providers\BladeServiceProvider;
use Huycds\Backends\Providers\ConsoleServiceProvider;
use Huycds\Backends\Providers\GeneratorServiceProvider;
use Huycds\Backends\Providers\HelperServiceProvider;
use Huycds\Backends\Providers\RepositoryServiceProvider;
use Illuminate\Support\ServiceProvider;

class BackendsServiceProvider extends ServiceProvider
{
    /**
     * @var bool Indicates if loading of the provider is deferred.
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/backends.php' => config_path('backends.php'),
        ], 'config');

        $this->app['backends']->register();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/backends.php', 'backends'
        );

        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(GeneratorServiceProvider::class);
        $this->app->register(HelperServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(BladeServiceProvider::class);

        $this->app->singleton('backends', function ($app) {
            $repository = $app->make(Repository::class);

            return new Backends($app, $repository);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['backends'];
    }

    public static function compiles()
    {
        $backends = app()->make('backends')->all();
        $files   = [];

        foreach ($backends as $backend) {
            $serviceProvider = backend_class($backend['slug'], 'Providers\\BackendServiceProvider');

            if (class_exists($serviceProvider)) {
                $files = array_merge($files, forward_static_call([$serviceProvider, 'compiles']));
            }
        }

        return array_map('realpath', $files);
    }
}
