<?php

namespace Huycds\Backends\Console\Generators;

use Huycds\Backends\Backends;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;

class MakeBackendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:backend
        {slug : The slug of the backend}
        {--Q|quick : Skip the make:backend wizard and use default values}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Caffeinated backend and bootstrap it';

    /**
     * The backends instance.
     *
     * @var Backends
     */
    protected $backend;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Array to store the configuration details.
     *
     * @var array
     */
    protected $container;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Backends $backend
     */
    public function __construct(Filesystem $files, Backends $backend)
    {
        parent::__construct();

        $this->files = $files;
        $this->backend = $backend;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['quick', null, InputOption::VALUE_OPTIONAL, 'Skip the make:backend wizard and use default values.', null],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->container['slug'] = str_slug($this->argument('slug'));
        $this->container['name'] = studly_case($this->container['slug']);
        $this->container['version'] = '1.0';
        $this->container['description'] = 'This is the description for the ' . $this->container['name'] . ' backend.';

        if ($this->option('quick')) {
            $this->container['basename'] = studly_case($this->container['slug']);
            $this->container['namespace'] = config('backends.namespace') . $this->container['basename'];
            return $this->generate();
        }

        $this->displayHeader('make_backend_introduction');

        $this->stepOne();
    }

    /**
     * Generate the backend.
     */
    protected function generate()
    {
        $steps = [
            'Generating backend...' => 'generateBackend',
            'Optimizing backend cache...' => 'optimizeBackends',
        ];

        $progress = new ProgressBar($this->output, count($steps));
        $progress->start();

        foreach ($steps as $message => $function) {
            $progress->setMessage($message);

            $this->$function();

            $progress->advance();
        }

        $progress->finish();

        event($this->container['slug'] . '.backend.made');

        $this->info("\nBackend generated successfully.");
    }

    /**
     * Pull the given stub file contents and display them on screen.
     *
     * @param string $file
     * @param string $level
     *
     * @return mixed
     */
    protected function displayHeader($file = '', $level = 'info')
    {
        $stub = $this->files->get(__DIR__ . '/../../../resources/stubs/console/' . $file . '.stub');

        return $this->$level($stub);
    }

    /**
     * Step 1: Configure backend manifest.
     *
     * @return mixed
     */
    protected function stepOne()
    {
        $this->displayHeader('make_backend_step_1');

        $this->container['name'] = $this->ask('Please enter the name of the backend:', $this->container['name']);
        $this->container['slug'] = $this->ask('Please enter the slug for the backend:', $this->container['slug']);
        $this->container['version'] = $this->ask('Please enter the backend version:', $this->container['version']);
        $this->container['description'] = $this->ask('Please enter the description of the backend:', $this->container['description']);
        $this->container['basename'] = studly_case($this->container['slug']);
        $this->container['namespace'] = config('backends.namespace') . $this->container['basename'];

        $this->comment('You have provided the following manifest information:');
        $this->comment('Name:                       ' . $this->container['name']);
        $this->comment('Slug:                       ' . $this->container['slug']);
        $this->comment('Version:                    ' . $this->container['version']);
        $this->comment('Description:                ' . $this->container['description']);
        $this->comment('Basename (auto-generated):  ' . $this->container['basename']);
        $this->comment('Namespace (auto-generated): ' . $this->container['namespace']);

        if ($this->confirm('If the provided information is correct, type "yes" to generate.')) {
            $this->comment('Thanks! That\'s all we need.');
            $this->comment('Now relax while your backend is generated.');

            $this->generate();
        } else {
            return $this->stepOne();
        }

        return true;
    }

    /**
     * Generate defined backend folders.
     */
    protected function generateBackend()
    {
        if (!$this->files->isDirectory(backend_path())) {
            $this->files->makeDirectory(backend_path());
        }

        $pathMap = config('backends.pathMap');
        $directory = backend_path(null, $this->container['basename']);
        $source = __DIR__ . '/../../../resources/stubs/backend';

        $this->files->makeDirectory($directory);

        $sourceFiles = $this->files->allFiles($source, true);

        if (!empty($pathMap)) {
            $search = array_keys($pathMap);
            $replace = array_values($pathMap);
        }

        foreach ($sourceFiles as $file) {
            $contents = $this->replacePlaceholders($file->getContents());
            $subPath = $file->getRelativePathname();

            if (!empty($pathMap)) {
                $subPath = str_replace($search, $replace, $subPath);
            }

            $filePath = $directory . '/' . $subPath;
            $dir = dirname($filePath);

            if (!$this->files->isDirectory($dir)) {
                $this->files->makeDirectory($dir, 0755, true);
            }

            $this->files->put($filePath, $contents);
        }
    }

    protected function replacePlaceholders($contents)
    {
        $find = [
            'DummyBasename',
            'DummyNamespace',
            'DummyName',
            'DummySlug',
            'DummyVersion',
            'DummyDescription',
        ];

        $replace = [
            $this->container['basename'],
            $this->container['namespace'],
            $this->container['name'],
            $this->container['slug'],
            $this->container['version'],
            $this->container['description'],
        ];

        return str_replace($find, $replace, $contents);
    }

    /**
     * Reset backend cache of enabled and disabled backends.
     */
    protected function optimizeBackends()
    {
        return $this->callSilent('backend:optimize');
    }
}
