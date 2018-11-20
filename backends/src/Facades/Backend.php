<?php

namespace Huycds\Backends\Facades;

use Illuminate\Support\Facades\Facade;

class Backend extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'backends';
    }
}
