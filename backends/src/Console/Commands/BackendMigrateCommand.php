<?php

namespace Huycds\Backends\Console\Commands;

use Huycds\Backends\Backends;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BackendMigrateCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations for a specific or all backends';

    /**
     * @var Backends
     */
    protected $backend;

    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * Create a new command instance.
     *
     * @param Migrator $migrator
     * @param Backends  $backend
     */
    public function __construct(Migrator $migrator, Backends $backend)
    {
        parent::__construct();

        $this->migrator = $migrator;
        $this->backend = $backend;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->prepareDatabase();

        if (!empty($this->argument('slug'))) {
            $backend = $this->backend->where('slug', $this->argument('slug'));

            if ($this->backend->isEnabled($backend['slug'])) {
                return $this->migrate($backend['slug']);
            } elseif ($this->option('force')) {
                return $this->migrate($backend['slug']);
            } else {
                return $this->error('Nothing to migrate.');
            }
        } else {
            if ($this->option('force')) {
                $backends = $this->backend->all();
            } else {
                $backends = $this->backend->enabled();
            }

            foreach ($backends as $backend) {
                $this->migrate($backend['slug']);
            }
        }
    }

    /**
     * Run migrations for the specified backend.
     *
     * @param string $slug
     *
     * @return mixed
     */
    protected function migrate($slug)
    {
        if ($this->backend->exists($slug)) {
            $backend = $this->backend->where('slug', $slug);
            $pretend = Arr::get($this->option(), 'pretend', false);
            $step = Arr::get($this->option(), 'step', false);
            $path = $this->getMigrationPath($slug);

            $this->migrator->setOutput($this->output)->run($path, ['pretend' => $pretend, 'step' => $step]);

            event($slug.'.backend.migrated', [$backend, $this->option()]);

            // Finally, if the "seed" option has been given, we will re-run the database
            // seed task to re-populate the database, which is convenient when adding
            // a migration and a seed at the same time, as it is only this command.
            if ($this->option('seed')) {
                $this->call('backend:seed', ['backend' => $slug, '--force' => true]);
            }
        } else {
            return $this->error('Backend does not exist.');
        }
    }

    /**
     * Get migration directory path.
     *
     * @param string $slug
     *
     * @return string
     */
    protected function getMigrationPath($slug)
    {
        return backend_path($slug, 'Database/Migrations');
    }

    /**
     * Prepare the migration database for running.
     */
    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->option('database'));

        if (!$this->migrator->repositoryExists()) {
            $options = ['--database' => $this->option('database')];

            $this->call('migrate:install', $options);
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run while in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
            ['step', null, InputOption::VALUE_NONE, 'Force the migrations to be run so they can be rolled back individually.'],
        ];
    }
}
