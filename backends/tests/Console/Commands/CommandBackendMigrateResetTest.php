<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendMigrateResetTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'migrate-reset', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_migrate_reset_a_backend()
    {
        $this->assertFalse(\Schema::hasTable('CustomCreateMigrationResetTable'));

        $this->artisan('make:backend:migration', ['slug' => 'migrate-reset', 'name' => 'CustomMigrateReset', '--create' => 'CustomCreateMigrationResetTable']);

        $this->artisan('backend:migrate', ['slug' => 'migrate-reset']);

        $this->assertTrue(\Schema::hasTable('CustomCreateMigrationResetTable'));

        $this->artisan('backend:migrate:reset', ['slug' => 'migrate-reset']);

        $this->assertFalse(\Schema::hasTable('CustomCreateMigrationResetTable'));
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('migrate-reset'));

        parent::tearDown();
    }
}