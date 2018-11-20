<?php

namespace Huycds\Backends\Providers;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
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
        $generators = [
            'command.make.backend'            => \Huycds\Backends\Console\Generators\MakeBackendCommand::class,
            'command.make.backend.controller' => \Huycds\Backends\Console\Generators\MakeControllerCommand::class,
            'command.make.backend.middleware' => \Huycds\Backends\Console\Generators\MakeMiddlewareCommand::class,
            'command.make.backend.migration'  => \Huycds\Backends\Console\Generators\MakeMigrationCommand::class,
            'command.make.backend.model'      => \Huycds\Backends\Console\Generators\MakeModelCommand::class,
            'command.make.backend.policy'     => \Huycds\Backends\Console\Generators\MakePolicyCommand::class,
            'command.make.backend.provider'   => \Huycds\Backends\Console\Generators\MakeProviderCommand::class,
            'command.make.backend.request'    => \Huycds\Backends\Console\Generators\MakeRequestCommand::class,
            'command.make.backend.seeder'     => \Huycds\Backends\Console\Generators\MakeSeederCommand::class,
            'command.make.backend.test'       => \Huycds\Backends\Console\Generators\MakeTestCommand::class,
        ];

        foreach ($generators as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
    }
}
