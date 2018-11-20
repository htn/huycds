<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeMigrationTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'migration', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_migration_with_custom_backend_namespace()
    {
        $this->artisan('make:backend:migration', ['slug' => 'migration', 'name' => 'CustomMigration']);

        $files = $this->finder->allFiles(backend_path('migration') . '/Database/Migrations');
        $this->finder->move($files[0], backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $migration = $this->finder->get(backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_can_generate_a_new_migration_with_default_backend_namespace()
    {
        $this->artisan('make:backend:migration', ['slug' => 'migration', 'name' => 'DefaultMigration']);

        $files = $this->finder->allFiles(backend_path('migration') . '/Database/Migrations');
        $this->finder->move($files[0], backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_default_migration_models_table.php');

        $migration = $this->finder->get(backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_default_migration_models_table.php');

        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_can_generate_a_new_migration_with_table_create()
    {
        $this->artisan('make:backend:migration', ['slug' => 'migration', 'name' => 'CustomMigration', '--create' => 'CustomCreateMigrationTable']);

        $files = $this->finder->allFiles(backend_path('migration') . '/Database/Migrations');
        $this->finder->move($files[0], backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $migration = $this->finder->get(backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_can_generate_a_new_migration_with_table_create_and_migrate()
    {
        $this->artisan('make:backend:migration', ['slug' => 'migration', 'name' => 'CustomMigration', '--create' => 'CustomCreateMigrationTable', '--table' => 'CustomTableMigrationTable']);

        $files = $this->finder->allFiles(backend_path('migration') . '/Database/Migrations');
        $this->finder->move($files[0], backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $migration = $this->finder->get(backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_can_generate_a_new_migration_with_table_migrate()
    {
        $this->artisan('make:backend:migration', ['slug' => 'migration', 'name' => 'CustomMigration', '--table' => 'CustomTableMigrationTable']);

        $files = $this->finder->allFiles(backend_path('migration') . '/Database/Migrations');
        $this->finder->move($files[0], backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $migration = $this->finder->get(backend_path('migration') . '/Database/Migrations/2018_06_18_000000_create_custom_migration_models_table.php');

        $this->assertMatchesSnapshot($migration);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('migration'));

        parent::tearDown();
    }
}