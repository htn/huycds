<?php

namespace Huycds\Backends\Providers;

use Huycds\Backends\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
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
        $this->registerDisableCommand();
        $this->registerEnableCommand();
        $this->registerListCommand();
        $this->registerMigrateCommand();
        $this->registerMigrateRefreshCommand();
        $this->registerMigrateResetCommand();
        $this->registerMigrateRollbackCommand();
        $this->registerOptimizeCommand();
        $this->registerSeedCommand();
    }

    /**
     * Register the backend:disable command.
     */
    protected function registerDisableCommand()
    {
        $this->app->singleton('command.backend.disable', function () {
            return new \Huycds\Backends\Console\Commands\BackendDisableCommand();
        });

        $this->commands('command.backend.disable');
    }

    /**
     * Register the backend:enable command.
     */
    protected function registerEnableCommand()
    {
        $this->app->singleton('command.backend.enable', function () {
            return new \Huycds\Backends\Console\Commands\BackendEnableCommand();
        });

        $this->commands('command.backend.enable');
    }

    /**
     * Register the backend:list command.
     */
    protected function registerListCommand()
    {
        $this->app->singleton('command.backend.list', function ($app) {
            return new \Huycds\Backends\Console\Commands\BackendListCommand($app['backends']);
        });

        $this->commands('command.backend.list');
    }

    /**
     * Register the backend:migrate command.
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.backend.migrate', function ($app) {
            return new \Huycds\Backends\Console\Commands\BackendMigrateCommand($app['migrator'], $app['backends']);
        });

        $this->commands('command.backend.migrate');
    }

    /**
     * Register the backend:migrate:refresh command.
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->singleton('command.backend.migrate.refresh', function () {
            return new \Huycds\Backends\Console\Commands\BackendMigrateRefreshCommand();
        });

        $this->commands('command.backend.migrate.refresh');
    }

    /**
     * Register the backend:migrate:reset command.
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->singleton('command.backend.migrate.reset', function ($app) {
            return new \Huycds\Backends\Console\Commands\BackendMigrateResetCommand($app['backends'], $app['files'], $app['migrator']);
        });

        $this->commands('command.backend.migrate.reset');
    }

    /**
     * Register the backend:migrate:rollback command.
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton('command.backend.migrate.rollback', function ($app) {
            $repository = $app['migration.repository'];
            $table = $app['config']['database.migrations'];

            $migrator = new Migrator($table, $repository, $app['db'], $app['files']);

            return new \Huycds\Backends\Console\Commands\BackendMigrateRollbackCommand($migrator, $app['backends']);
        });

        $this->commands('command.backend.migrate.rollback');
    }

    /**
     * Register the backend:optimize command.
     */
    protected function registerOptimizeCommand()
    {
        $this->app->singleton('command.backend.optimize', function () {
            return new \Huycds\Backends\Console\Commands\BackendOptimizeCommand();
        });

        $this->commands('command.backend.optimize');
    }

    /**
     * Register the backend:seed command.
     */
    protected function registerSeedCommand()
    {
        $this->app->singleton('command.backend.seed', function ($app) {
            return new \Huycds\Backends\Console\Commands\BackendSeedCommand($app['backends']);
        });

        $this->commands('command.backend.seed');
    }
}
