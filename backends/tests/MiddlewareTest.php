<?php

namespace Huycds\Backends\Tests;

class MiddlewareTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'middleware', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_check_if_it_has_invalid_backend_with_identify_backend_middleware()
    {
        $this->app['router']->aliasMiddleware('backend', \Huycds\Backends\Middleware\IdentifyBackend::class);

        $this->app['router']->group(
            ['middleware' => [\Illuminate\Session\Middleware\StartSession::class, 'backend:controller']],
            function () {
                $this->app['router']->get('has-invalid-identify-middleware', function () {
                    return session()->get('backend');
                });
            }
        );

        $content = $this->call('get', 'has-invalid-identify-middleware')->getContent();

        $this->assertSame(
            '[]',
            $content
        );

        $this->assertSame(
            '[]',
            session()->get('backend')->toJson()
        );
    }

    /** @test */
    public function it_can_check_if_it_has_no_identify_backend_middleware()
    {
        $this->app['router']->get('has-no-identify-middleware', function () {
            return session()->get('backend');
        });

        $content = $this->call('get', 'has-no-identify-middleware')->getContent();

        $this->assertSame('', $content);

        $this->assertFalse(session()->has('backend'));
    }

    /** @test */
    public function it_can_check_if_it_has_valid_backend_with_identify_backend_middleware()
    {
        $this->app['router']->aliasMiddleware('backend', \Huycds\Backends\Middleware\IdentifyBackend::class);

        $this->app['router']->group(
            ['middleware' => [\Illuminate\Session\Middleware\StartSession::class, 'backend:middleware']],
            function () {
                $this->app['router']->get('has-valid-identify-middleware', function () {
                    return session()->get('backend');
                });
            }
        );

        $content = $this->call('get', 'has-valid-identify-middleware')->getContent();

        $this->assertSame(
            '{"basename":"Middleware","name":"Middleware","slug":"middleware","version":"1.0","description":"This is the description for the Middleware backend.","id":2915276403,"enabled":true,"order":9001}',
            $content
        );

        $this->assertSame(
            '{"basename":"Middleware","name":"Middleware","slug":"middleware","version":"1.0","description":"This is the description for the Middleware backend.","id":2915276403,"enabled":true,"order":9001}',
            session()->get('backend')->toJson()
        );
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('middleware'));

        parent::tearDown();
    }
}