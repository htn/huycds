<?php

namespace Huycds\Backends\Traits;

trait MigrationTrait
{
    /**
     * Require (once) all migration files for the supplied backend.
     *
     * @param string $backend
     */
    protected function requireMigrations($backend)
    {
        $path = $this->getMigrationPath($backend);

        $migrations = $this->laravel['files']->glob($path.'*_*.php');

        foreach ($migrations as $migration) {
            $this->laravel['files']->requireOnce($migration);
        }
    }

    /**
     * Get migration directory path.
     *
     * @param string $backend
     *
     * @return string
     */
    protected function getMigrationPath($backend)
    {
        return backend_path($backend, 'Database/Migrations');
    }
}
