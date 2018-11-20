<?php

namespace Huycds\Backends\Console\Commands;

use Huycds\Backends\Backends;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BackendSeedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with records for a specific or all backends';

    /**
     * @var Backends
     */
    protected $backend;

    /**
     * Create a new command instance.
     *
     * @param Backends $backend
     */
    public function __construct(Backends $backend)
    {
        parent::__construct();

        $this->backend = $backend;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->argument('slug');

        if (isset($slug)) {
            if (!$this->backend->exists($slug)) {
                return $this->error('Backend does not exist.');
            }

            if ($this->backend->isEnabled($slug)) {
                $this->seed($slug);
            } elseif ($this->option('force')) {
                $this->seed($slug);
            }

            return;
        } else {
            if ($this->option('force')) {
                $backends = $this->backend->all();
            } else {
                $backends = $this->backend->enabled();
            }

            foreach ($backends as $backend) {
                $this->seed($backend['slug']);
            }
        }
    }

    /**
     * Seed the specific backend.
     *
     * @param string $backend
     *
     * @return array
     */
    protected function seed($slug)
    {
        $backend = $this->backend->where('slug', $slug);
        $params = [];
        $namespacePath = $this->backend->getNamespace();
        $rootSeeder = $backend['basename'].'DatabaseSeeder';
        $fullPath = $namespacePath.'\\'.$backend['basename'].'\Database\Seeds\\'.$rootSeeder;

        if (class_exists($fullPath)) {
            if ($this->option('class')) {
                $params['--class'] = $this->option('class');
            } else {
                $params['--class'] = $fullPath;
            }

            if ($option = $this->option('database')) {
                $params['--database'] = $option;
            }

            if ($option = $this->option('force')) {
                $params['--force'] = $option;
            }

            $this->call('db:seed', $params);

            event($slug.'.backend.seeded', [$backend, $this->option()]);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [['slug', InputArgument::OPTIONAL, 'Backend slug.']];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the backend\'s root seeder.'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run while in production.'],
        ];
    }
}
