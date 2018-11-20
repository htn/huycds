<?php

namespace Huycds\Backends\Console\Commands;

use Huycds\Backends\Backends;
use Illuminate\Console\Command;

class BackendListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backend:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all application backends';

    /**
     * @var Backends
     */
    protected $backend;

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = ['#', 'Name', 'Slug', 'Description', 'Status'];

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
        $backends = $this->backend->all();

        if (count($backends) == 0) {
            return $this->error("Your application doesn't have any backends.");
        }

        $this->displayBackends($this->getBackends());
    }

    /**
     * Get all backends.
     *
     * @return array
     */
    protected function getBackends()
    {
        $backends = $this->backend->all();
        $results = [];

        foreach ($backends as $backend) {
            $results[] = $this->getBackendInformation($backend);
        }

        return array_filter($results);
    }

    /**
     * Returns backend manifest information.
     *
     * @param string $backend
     *
     * @return array
     */
    protected function getBackendInformation($backend)
    {
        return [
            '#'           => $backend['order'],
            'name'        => isset($backend['name']) ? $backend['name'] : '',
            'slug'        => $backend['slug'],
            'description' => isset($backend['description']) ? $backend['description'] : '',
            'status'      => ($this->backend->isEnabled($backend['slug'])) ? 'Enabled' : 'Disabled',
        ];
    }

    /**
     * Display the backend information on the console.
     *
     * @param array $backends
     */
    protected function displayBackends(array $backends)
    {
        $this->table($this->headers, $backends);
    }
}
