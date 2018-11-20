<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendOptimizeTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'optimize', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_optimize_a_backend()
    {
        $before = file_get_contents(storage_path('app/backends.json'));

        $this->assertSame(
            '{
    "Optimize": {
        "basename": "Optimize",
        "name": "Optimize",
        "slug": "optimize",
        "version": "1.0",
        "description": "This is the description for the Optimize backend.",
        "id": 3797040228,
        "enabled": true,
        "order": 9001
    }
}',
            $before
        );

        //

        file_put_contents(backend_path('optimize').'/backend.json',
            json_encode(
                array_merge(
                    json_decode(file_get_contents(backend_path('optimize').'/backend.json'), true),
                    ['version' => '1.3.3.7']
                )
            , JSON_PRETTY_PRINT)
        );

        $this->artisan('backend:optimize');

        //

        $optimized = file_get_contents(storage_path('app/backends.json'));

        $this->assertSame(
            '{
    "Optimize": {
        "basename": "Optimize",
        "name": "Optimize",
        "slug": "optimize",
        "version": "1.3.3.7",
        "description": "This is the description for the Optimize backend.",
        "id": 3797040228,
        "enabled": true,
        "order": 9001
    }
}',
            $optimized
        );
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('optimize'));

        parent::tearDown();
    }
}