<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendMigrateRefreshTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'migrate-refresh', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_migrate_refresh_a_backend()
    {
        $this->assertFalse(\Schema::hasTable('CustomCreateMigrationRefreshTable'));

        $this->artisan('make:backend:migration', ['slug' => 'migrate-refresh', 'name' => 'CustomMigrateRefresh', '--create' => 'CustomCreateMigrationRefreshTable']);

        $this->artisan('backend:migrate', ['slug' => 'migrate-refresh']);

        $this->assertTrue(\Schema::hasTable('CustomCreateMigrationRefreshTable'));

        $this->artisan('backend:migrate:refresh', ['slug' => 'migrate-refresh']);

        $this->assertTrue(\Schema::hasTable('CustomCreateMigrationRefreshTable'));
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('migrate-refresh'));

        parent::tearDown();
    }
}