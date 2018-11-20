<?php

use Huycds\Backends\Exceptions\BackendNotFoundException;

if (!function_exists('backend_path')) {
    /**
     * Return the path to the given backend file.
     *
     * @param string $slug
     * @param string $file
     *
     * @return string
     * @throws \Huycds\Backends\Exceptions\BackendNotFoundException
     */
    function backend_path($slug = null, $file = '')
    {
        $backendsPath = config('backends.path');
        $pathMap = config('backends.pathMap');

        if (!empty($file) && !empty($pathMap)) {
            $file = str_replace(
                array_keys($pathMap),
                array_values($pathMap),
                $file
            );
        }

        $filePath = $file ? '/' . ltrim($file, '/') : '';

        if (is_null($slug)) {
            if (empty($file)) {
                return $backendsPath;
            }

            return $backendsPath . $filePath;
        }

        $backend = Backend::where('slug', $slug);

        if (is_null($backend)) {
            throw new BackendNotFoundException($slug);
        }

        return $backendsPath . '/' . $backend['basename'] . $filePath;
    }
}

if (!function_exists('backend_class')) {
    /**
     * Return the full path to the given backend class.
     *
     * @param string $slug
     * @param string $class
     *
     * @return string
     * @throws \Huycds\Backends\Exceptions\BackendNotFoundException
     */
    function backend_class($slug, $class)
    {
        $backend = Backend::where('slug', $slug);

        if (is_null($backend)) {
            throw new BackendNotFoundException($slug);
        }

        $namespace = config('backends.namespace') . $backend['basename'];

        return "{$namespace}\\{$class}";
    }
}
