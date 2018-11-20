<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Path to Backends
    |--------------------------------------------------------------------------
    |
    | Define the path where you'd like to store your backends. Note that if you
    | choose a path that's outside of your public directory, you will need to
    | copy your backend assets (CSS, images, etc.) to your public directory.
    |
    */

    'path' => app_path('Backends'),

    /*
    |--------------------------------------------------------------------------
    | Backends Default State
    |--------------------------------------------------------------------------
    |
    | When a previously unknown backend is added, if it doesn't have an 'enabled'
    | state set then this is the value which it will default to. If this is
    | not provided then the backend will default to being 'enabled'.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Backends Base Namespace
    |--------------------------------------------------------------------------
    |
    | Define the base namespace for your backends. Be sure to update this value
    | if you move your backends directory to a new path. This is primarily used
    | by the backend:make Artisan command.
    |
    */

    'namespace' => 'App\Backends\\',

    /*
    |--------------------------------------------------------------------------
    | Backends Default Service Provider class name
    |--------------------------------------------------------------------------
    |
    | Define class name to use as default backend service provider.
    |
    */

    'provider_class' => 'Providers\\BackendServiceProvider',

    /*
    |--------------------------------------------------------------------------
    | Default Backend Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the backend storage driver that will be utilized.
    | This driver manages the retrieval and management of backend properties.
    | Setting this to custom allows you to specify your own driver instance.
    |
    | Supported: "local" or "custom"
    |
    */

    'driver' => 'local',

    /*
     |--------------------------------------------------------------------------
     | Custom Backend Driver
     |--------------------------------------------------------------------------
     |
     | Using a custom backend driver, the 'driver' value need to be set to 'custom'
     | The path to the driver need to be set in addition at custom_driver.
     |
     | @warn: This value will be only considered if driver is set to custom.
     |
     */

    // 'custom_driver' => 'Huycds\Backends\Repositories\LocalRepository',

    /*
    |--------------------------------------------------------------------------
    | Remap Backend Subdirectories
    |--------------------------------------------------------------------------
    |
    | Redefine how backend directories are structured. The mapping here will
    | be respected by all commands and generators.
    |
    */

    'pathMap' => [
        // To change where migrations go, specify the default
        // location as the key and the new location as the value:
        // 'Database/Migrations' => 'src/Database/Migrations',
    ],
];
