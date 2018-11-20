<?php

namespace Huycds\Backends\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class BackendEnableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable a backend';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->argument('slug');

        if ($this->laravel['backends']->isDisabled($slug)) {
            $this->laravel['backends']->enable($slug);

            $backend = $this->laravel['backends']->where('slug', $slug);

            event($slug.'.backend.enabled', [$backend, null]);

            $this->info('backend was enabled successfully.');
        } else {
            $this->comment('backend is already enabled.');
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
            ['slug', InputArgument::REQUIRED, 'backend slug.'],
        ];
    }
}
