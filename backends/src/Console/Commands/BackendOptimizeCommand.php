<?php

namespace Huycds\Backends\Console\Commands;

use Illuminate\Console\Command;

class BackendOptimizeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the backend cache for better performance';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Generating optimized backend cache');

        $this->laravel['backends']->optimize();

        event('backends.optimized', [$this->laravel['backends']->all()]);
    }
}
