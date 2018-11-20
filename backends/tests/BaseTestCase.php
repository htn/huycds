<?php

namespace Huycds\Backends\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class BaseTestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Huycds\Backends\BackendsServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Backend' => \Huycds\Backends\Facades\Backend::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));

        $app['config']->set('view.paths', [__DIR__.'/resources/views']);

        $app['config']->set('backends.path', base_path('backends'));
        $app['config']->set('backends.namespace', 'App\\Backends\\');
        $app['config']->set('backends.driver', 'local');
    }
}