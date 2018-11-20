<?php

namespace Huycds\Backends\Console\Generators;

use Huycds\Backends\Console\GeneratorCommand;

class MakeSeederCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:backend:seeder
    	{slug : The slug of the backend.}
    	{name : The name of the seeder class.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new backend seeder class';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Backend seeder';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/seeder.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        return backend_path($this->argument('slug'), 'Database/Seeds/'.$name.'.php');
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function qualifyClass($name)
    {
        return $name;
    }

    /**
     * Replace namespace in seeder stub.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return backend_class($this->argument('slug'), 'Database\Seeds');
    }
}
