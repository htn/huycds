<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeSeederTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'seeder', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_seeder_with_default_backend_namespace()
    {
        $this->artisan('make:backend:seeder', ['slug' => 'seeder', 'name' => 'DefaultSeeder']);

        $file = $this->finder->get(backend_path('seeder').'/Database/Seeds/DefaultSeeder.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_seeder_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\CustomSeederNamespace\\');

        $this->artisan('make:backend:seeder', ['slug' => 'seeder', 'name' => 'CustomSeeder']);

        $file = $this->finder->get(backend_path('seeder').'/Database/Seeds/CustomSeeder.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('seeder'));

        parent::tearDown();
    }
}