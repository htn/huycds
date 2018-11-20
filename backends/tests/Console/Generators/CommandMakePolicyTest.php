<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakePolicyTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'policy', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_policy_with_default_backend_namespace()
    {
        $this->artisan('make:backend:policy', ['slug' => 'policy', 'name' => 'DefaultPolicy']);

        $file = $this->finder->get(backend_path('policy').'/Policies/DefaultPolicy.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_policy_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\CustomPolicyNamespace\\');

        $this->artisan('make:backend:policy', ['slug' => 'policy', 'name' => 'CustomPolicy']);

        $file = $this->finder->get(backend_path('policy').'/Policies/CustomPolicy.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('policy'));

        parent::tearDown();
    }
}