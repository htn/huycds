<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendMigrateRollbackTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'migrate-rollback', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_migrate_rollback_a_backend()
    {
        $this->assertFalse(\Schema::hasTable('CustomCreateMigrationRollbackTable'));

        $this->artisan('make:backend:migration', ['slug' => 'migrate-rollback', 'name' => 'CustomMigrateRollback', '--create' => 'CustomCreateMigrationRollbackTable']);

        $this->artisan('backend:migrate', ['slug' => 'migrate-rollback']);

        $this->assertTrue(\Schema::hasTable('CustomCreateMigrationRollbackTable'));

        $this->artisan('backend:migrate:rollback', ['slug' => 'migrate-rollback']);

        $this->assertFalse(\Schema::hasTable('CustomCreateMigrationRollbackTable'));
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('migrate-rollback'));

        parent::tearDown();
    }
}