<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendMigrateTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'migrate', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_migrate_a_backend()
    {
        $this->assertFalse(\Schema::hasTable('CustomCreateMigrationTable'));

        $this->artisan('make:backend:migration', ['slug' => 'migrate', 'name' => 'CustomMigrate', '--create' => 'CustomCreateMigrationTable']);

        $this->artisan('backend:migrate', ['slug' => 'migrate']);

        $this->assertTrue(\Schema::hasTable('CustomCreateMigrationTable'));
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('migrate'));

        parent::tearDown();
    }
}