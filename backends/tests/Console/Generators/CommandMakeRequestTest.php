<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeRequestTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'request', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_request_with_default_backend_namespace()
    {
        $this->artisan('make:backend:request', ['slug' => 'request', 'name' => 'DefaultRequest']);

        $file = $this->finder->get(backend_path('request').'/Http/Requests/DefaultRequest.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_request_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\CustomRequestNamespace\\');

        $this->artisan('make:backend:request', ['slug' => 'request', 'name' => 'CustomRequest']);

        $file = $this->finder->get(backend_path('request').'/Http/Requests/CustomRequest.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('request'));

        parent::tearDown();
    }
}