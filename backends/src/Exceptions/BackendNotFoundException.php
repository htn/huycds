<?php

namespace Huycds\Backends\Exceptions;

class BackendNotFoundException extends \Exception
{
    /**
     * BackendNotFoundException constructor.
     * @param $slug
     */
    public function __construct($slug)
    {
        parent::__construct('Backend with slug name [' . $slug . '] not found');
    }
}