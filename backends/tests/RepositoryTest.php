<?php

namespace Huycds\Backends\Tests;

use Illuminate\Support\Collection;

class RepositoryTest extends BaseTestCase
{
    protected $finder;

    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->repository = new \Huycds\Backends\Backends(
            $this->app,
            $this->app->make(\Huycds\Backends\Contracts\Repository::class)
        );

        $this->artisan('make:backend', ['slug' => 'RepositoryMod2', '--quick' => 'quick']);

        $this->artisan('make:backend', ['slug' => 'RepositoryMod1', '--quick' => 'quick']);

        $this->artisan('make:backend', ['slug' => 'RepositoryMod3', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_check_if_backend_is_disabled()
    {
        $this->assertFalse($this->repository->isDisabled('repositorymod1'));

        $this->repository->disable('repositorymod1');

        $this->assertTrue($this->repository->isDisabled('repositorymod1'));
    }

    /** @test */
    public function it_can_check_if_backend_is_enabled()
    {
        $this->assertTrue($this->repository->isEnabled('repositorymod1'));

        $this->repository->disable('repositorymod1');

        $this->assertFalse($this->repository->isEnabled('repositorymod1'));
    }

    /** @test */
    public function it_can_check_if_the_backend_exists()
    {
        $this->assertTrue($this->repository->exists('repositorymod1'));

        $this->assertFalse($this->repository->exists('repositorymod4'));
    }

    /** @test */
    public function it_can_count_the_backends()
    {
        $this->assertSame(3, (int)$this->repository->count());
    }

    /** @test */
    public function it_can_get_a_collection_of_disabled_backends()
    {
        $this->assertSame(0, (int)$this->repository->disabled()->count());

        $this->repository->disable('repositorymod1');

        $this->assertSame(1, (int)$this->repository->disabled()->count());
    }

    /** @test */
    public function it_can_get_a_collection_of_enabled_backends()
    {
        $this->assertSame(3, (int)$this->repository->enabled()->count());

        $this->repository->disable('repositorymod1');

        $this->assertSame(2, (int)$this->repository->enabled()->count());
    }

    /** @test */
    public function it_can_get_a_backend_based_on_where()
    {
        $slug = $this->repository->where('slug', 'repositorymod1');

        $this->assertInstanceOf(Collection::class, $slug);

        $this->assertCount(8, $slug);

        //

        $basename = $this->repository->where('basename', 'Repositorymod1');

        $this->assertInstanceOf(Collection::class, $basename);

        $this->assertCount(8, $basename);

        //

        $name = $this->repository->where('name', 'Repositorymod1');

        $this->assertInstanceOf(Collection::class, $name);

        $this->assertCount(8, $name);
    }

    /** @test */
    public function it_can_get_all_the_backends()
    {
        $this->assertCount(3, $this->repository->all());

        $this->assertInstanceOf(Collection::class, $this->repository->all());
    }

    /** @test */
    public function it_can_get_correct_backend_and_manifest_for_legacy_backends()
    {
        $this->artisan('make:backend', ['slug' => 'barbiz', '--quick' => 'quick']);

        // Quick and fast way to simulate legacy Backend FolderStructure
        // https://github.com/caffeinated/backends/pull/224
        rename(realpath(backend_path('barbiz')), realpath(backend_path()) . '/BarBiz');
        file_put_contents(realpath(backend_path()) . '/BarBiz/backend.json', json_encode(array(
            'name' => 'BarBiz', 'slug' => 'BarBiz', 'version' => '1.0', 'description' => '',
        ), JSON_PRETTY_PRINT));

        $this->assertSame(
            '{"name":"BarBiz","slug":"BarBiz","version":"1.0","description":""}',
            json_encode($this->repository->getManifest('BarBiz'))
        );

        $this->assertSame(
            realpath(backend_path() . '/BarBiz'),
            realpath($this->repository->getBackendPath('BarBiz'))
        );

        $this->finder->deleteDirectory(backend_path() . '/BarBiz');
    }

    /** @test */
    public function it_can_get_correct_slug_exists_for_legacy_backends()
    {
        $this->artisan('make:backend', ['slug' => 'foobar', '--quick' => 'quick']);

        // Quick and fast way to simulate legacy Backend FolderStructure
        // https://github.com/caffeinated/backends/pull/279
        // https://github.com/caffeinated/backends/pull/349
        rename(realpath(backend_path('foobar')), realpath(backend_path()) . '/FooBar');
        file_put_contents(realpath(backend_path()) . '/FooBar/backend.json', json_encode(array(
            'name' => 'FooBar', 'slug' => 'FooBar', 'version' => '1.0', 'description' => '',
        ), JSON_PRETTY_PRINT));

        $this->assertTrue($this->repository->exists('FooBar'));

        $this->finder->deleteDirectory(backend_path() . '/FooBar');
    }

    /** @test */
    public function it_can_get_custom_backends_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\Foo\\Bar\\Baz\\Tests');

        $this->assertSame('App\Foo\Bar\Baz\Tests', $this->repository->getNamespace());

        $this->app['config']->set('backends.namespace', 'App\\Foo\\Baz\\Bar\\Tests\\');

        $this->assertSame('App\Foo\Baz\Bar\Tests', $this->repository->getNamespace());
    }

    /** @test */
    public function it_can_get_default_backends_namespace()
    {
        $this->assertSame('App\Backends', $this->repository->getNamespace());
    }

    /** @test */
    public function it_can_get_default_backends_path()
    {
        $this->assertSame(base_path() . '/backends', $this->repository->getPath());
    }

    /** @test */
    public function it_can_get_manifest_of_backend()
    {
        $manifest = $this->repository->getManifest('repositorymod1');

        $this->assertSame(
            '{"name":"Repositorymod1","slug":"repositorymod1","version":"1.0","description":"This is the description for the Repositorymod1 backend."}',
            $manifest->toJson()
        );
    }

    /** @test */
    public function it_can_get_backend_path_of_backend()
    {
        $path = $this->repository->getBackendPath('repositorymod1');

        $this->assertSame(
            base_path() . '/backends/Repositorymod1/',
            $path
        );
    }

    /** @test */
    public function it_can_get_property_of_backend()
    {
        $this->assertSame('Repositorymod1', $this->repository->get('repositorymod1::name'));

        $this->assertSame('1.0', $this->repository->get('repositorymod2::version'));

        $this->assertSame('This is the description for the Repositorymod3 backend.', $this->repository->get('repositorymod3::description'));
    }

    /** @test */
    public function it_can_get_the_backends_slugs()
    {
        $this->assertCount(3, $this->repository->slugs());

        $this->repository->slugs()->each(function ($key, $value) {
            $this->assertSame('repositorymod' . ($value + 1), $key);
        });
    }

    /** @test */
    public function it_can_set_custom_backends_path_in_runtime_mode()
    {
        $this->repository->setPath(base_path('tests/runtime/backends'));

        $this->assertSame(
            base_path() . '/tests/runtime/backends',
            $this->repository->getPath()
        );
    }

    /** @test */
    public function it_can_set_property_of_backend()
    {
        $this->assertSame('Repositorymod1', $this->repository->get('repositorymod1::name'));

        $this->repository->set('repositorymod1::name', 'FooBarRepositorymod1');

        $this->assertSame('FooBarRepositorymod1', $this->repository->get('repositorymod1::name'));

        //

        $this->assertSame('1.0', $this->repository->get('repositorymod3::version'));

        $this->repository->set('repositorymod3::version', '1.3.3.7');

        $this->assertSame('1.3.3.7', $this->repository->get('repositorymod3::version'));
    }

    /** @test */
    public function it_can_sortby_asc_slug_the_backends()
    {
        $sortByAsc = array_keys($this->repository->sortby('slug')->toArray());

        $this->assertSame($sortByAsc[0], 'Repositorymod1');
        $this->assertSame($sortByAsc[1], 'Repositorymod2');
        $this->assertSame($sortByAsc[2], 'Repositorymod3');
    }

    /** @test */
    public function it_can_sortby_desc_slug_the_backends()
    {
        $sortByAsc = array_keys($this->repository->sortbyDesc('slug')->toArray());

        $this->assertSame($sortByAsc[0], 'Repositorymod3');
        $this->assertSame($sortByAsc[1], 'Repositorymod2');
        $this->assertSame($sortByAsc[2], 'Repositorymod1');
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function it_will_throw_exception_by_invalid_json_manifest_file()
    {
        file_put_contents(realpath(backend_path()) . '/Repositorymod1/backend.json', 'invalidjsonformat');

        $manifest = $this->repository->getManifest('repositorymod1');
    }

    /**
     * @test
     * @expectedException \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function it_will_throw_file_not_found_exception_by_unknown_backend()
    {
        $manifest = $this->repository->getManifest('unknown');
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('repositorymod1'));

        $this->finder->deleteDirectory(backend_path('repositorymod2'));

        $this->finder->deleteDirectory(backend_path('repositorymod3'));

        parent::tearDown();
    }
}