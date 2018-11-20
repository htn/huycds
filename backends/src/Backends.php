<?php

namespace Huycds\Backends;

use Huycds\Backends\Contracts\Repository;
use Huycds\Backends\Exceptions\BackendNotFoundException;
use Illuminate\Foundation\Application;

class Backends
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Create a new Backends instance.
     *
     * @param Application $app
     * @param Repository  $repository
     */
    public function __construct(Application $app, Repository $repository)
    {
        $this->app = $app;
        $this->repository = $repository;
    }

    /**
     * Register the backend service provider file from all backends.
     *
     * @return void
     */
    public function register()
    {
        $backends = $this->repository->enabled();

        $backends->each(function ($backend) {
            try {
                $this->registerServiceProvider($backend);

                $this->autoloadFiles($backend);
            } catch (BackendNotFoundException $e) {
                //
            }
        });
    }

    /**
     * Register the backend service provider.
     *
     * @param array $backend
     *
     * @return void
     */
    private function registerServiceProvider($backend)
    {
        $serviceProvider = backend_class($backend['slug'], config('backends.provider_class', 'Providers\\BackendServiceProvider'));

        if (class_exists($serviceProvider)) {
            $this->app->register($serviceProvider);
        }
    }

    /**
     * Autoload custom backend files.
     *
     * @param array $backend
     *
     * @return void
     */
    private function autoloadFiles($backend)
    {
        if (isset($backend['autoload'])) {
            foreach ($backend['autoload'] as $file) {
                $path = backend_path($backend['slug'], $file);

                if (file_exists($path)) {
                    include $path;
                }
            }
        }
    }

    /**
     * Oh sweet sweet magical method.
     *
     * @param string $method
     * @param mixed  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->repository, $method], $arguments);
    }
}
