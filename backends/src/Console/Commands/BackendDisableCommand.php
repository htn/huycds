<?php

namespace Huycds\Backends\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class BackendDisableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a backend';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->argument('slug');

        if ($this->laravel['backends']->isEnabled($slug)) {
            $this->laravel['backends']->disable($slug);

            $backend = $this->laravel['backends']->where('slug', $slug);

            event($slug.'.backend.disabled', [$backend, null]);

            $this->info('Backend was disabled successfully.');
        } else {
            $this->comment('Backend is already disabled.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['slug', InputArgument::REQUIRED, 'Backend slug.'],
        ];
    }
}
