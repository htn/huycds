<?php

namespace Huycds\Backends\Console\Commands;

use Huycds\Backends\Backends;
use Huycds\Backends\Traits\MigrationTrait;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BackendMigrateRollbackCommand extends Command
{
    use MigrationTrait, ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:migrate:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the last database migrations for a specific or all backends';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * @var Backends
     */
    protected $backend;

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
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->migrator->setConnection($this->option('database'));

        $paths = $this->getMigrationPaths();
        $this->migrator->setOutput($this->output)->rollback(
            $paths, ['pretend' => $this->option('pretend'), 'step' => (int) $this->option('step')]
        );
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
            ['step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted.'],
        ];
    }

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        $slug = $this->argument('slug');
        $paths = [];

        if ($slug) {
            $paths[] = backend_path($slug, 'Database/Migrations');
        } else {
            foreach ($this->backend->all() as $backend) {
                $paths[] = backend_path($backend['slug'], 'Database/Migrations');
            }
        }

        return $paths;
    }
}
