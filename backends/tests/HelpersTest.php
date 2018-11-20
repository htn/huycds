<?php

namespace Huycds\Backends\Tests;

class HelpersTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'helper', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_get_backend_path()
    {
        $this->assertSame(base_path().'/backends/Helper', backend_path('helper'));
    }

    /** @test */
    public function it_can_get_backend_path_files()
    {
        $this->assertSame(base_path().'/backends/Helper/Database', backend_path('helper', 'Database'));
        $this->assertSame(base_path().'/backends/Helper/Database/Factories', backend_path('helper', 'Database/Factories'));
        $this->assertSame(base_path().'/backends/Helper/Database/Migrations', backend_path('helper', 'Database/Migrations'));
        $this->assertSame(base_path().'/backends/Helper/Database/Seeds', backend_path('helper', 'Database/Seeds'));

        $this->assertSame(base_path().'/backends/Helper/Providers/BackendServiceProvider.php', backend_path('helper', 'Providers/BackendServiceProvider.php'));
        $this->assertSame(base_path().'/backends/Helper/Providers/RouteServiceProvider.php', backend_path('helper', 'Providers/RouteServiceProvider.php'));

        $this->assertSame(base_path().'/backends/Helper/Routes/api.php', backend_path('helper', 'Routes/api.php'));
        $this->assertSame(base_path().'/backends/Helper/Routes/web.php', backend_path('helper', 'Routes/web.php'));
    }

    /** @test */
    public function it_can_get_backend_class()
    {
        $this->assertSame('App\Backends\Helper\Database\Factories', backend_class('helper', 'Database\\Factories'));
        $this->assertSame('App\Backends\Helper\Database\Migrations', backend_class('helper', 'Database\\Migrations'));
        $this->assertSame('App\Backends\Helper\Database\Seeds', backend_class('helper', 'Database\\Seeds'));

        $this->assertSame('App\Backends\Helper\Http\Controllers', backend_class('helper', 'Http\\Controllers'));

        $this->assertSame('App\Backends\Helper\Http\Middleware', backend_class('helper', 'Http\Middleware'));
        $this->assertSame('App\Backends\Helper\Http\Middleware', backend_class('helper', 'Http\\Middleware'));

        $this->assertSame('App\Backends\Helper\Providers', backend_class('helper', 'Providers'));
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('helper'));

        parent::tearDown();
    }
}