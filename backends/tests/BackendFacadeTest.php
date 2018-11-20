<?php

namespace Huycds\Backends\Tests;

use Backend;

class BackendFacadeTest extends BaseTestCase
{
    /** @test */
    public function it_can_work_with_container()
    {
        $this->assertInstanceOf(\Huycds\Backends\Backends::class, $this->app['backends']);
    }

    /** @test */
    public function it_can_work_with_facade()
    {
        $this->assertSame('Huycds\Backends\Facades\Backend', (new \ReflectionClass(Backend::class))->getName());
    }
}