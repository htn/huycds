<?php

namespace Huycds\Backends\Tests;

class BladeTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'blade', '--quick' => 'quick']);
    }

    /** @test */
    public function it_has_backend_if_backend_exists_and_is_enabled()
    {
        $this->artisan('backend:enable', ['slug' => 'blade']);

        $this->assertEquals('has backend', $this->renderView('backend', ['backend' => 'blade']));
    }

    /** @test */
    public function it_has_no_backend_if_backend_dont_exists()
    {
        $this->assertEquals('no backend', $this->renderView('backend', ['backend' => 'dontexists']));
    }

    /** @test */
    public function it_has_no_backend_if_backend_exists_but_is_not_enabled()
    {
        $this->artisan('backend:disable', ['slug' => 'blade']);

        $this->assertEquals('no backend', $this->renderView('backend', ['backend' => 'blade']));
    }

    protected function renderView($view, $parameters)
    {
        $this->artisan('view:clear');

        return trim((string)(view($view)->with($parameters)));
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('blade'));

        parent::tearDown();
    }
}
