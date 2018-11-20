<?php

namespace Huycds\Backends\Repositories;

class LocalRepository extends Repository
{
    /**
     * Get all backends.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->getCache()->sortBy('order');
    }

    /**
     * Get all backend slugs.
     *
     * @return Collection
     */
    public function slugs()
    {
        $slugs = collect();

        $this->all()->each(function ($item, $key) use ($slugs) {
            $slugs->push(strtolower($item['slug']));
        });

        return $slugs;
    }

    /**
     * Get backends based on where clause.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Collection
     */
    public function where($key, $value)
    {
        return collect($this->all()->where($key, $value)->first());
    }

    /**
     * Sort backends by given key in ascending order.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function sortBy($key)
    {
        $collection = $this->all();

        return $collection->sortBy($key);
    }

    /**
     * Sort backends by given key in ascending order.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function sortByDesc($key)
    {
        $collection = $this->all();

        return $collection->sortByDesc($key);
    }

    /**
     * Determines if the given backend exists.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function exists($slug)
    {
        return ($this->slugs()->contains($slug) || $this->slugs()->contains(str_slug($slug)));
    }

    /**
     * Returns count of all backends.
     *
     * @return int
     */
    public function count()
    {
        return $this->all()->count();
    }

    /**
     * Get a backend property value.
     *
     * @param string $property
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($property, $default = null)
    {
        list($slug, $key) = explode('::', $property);

        $backend = $this->where('slug', $slug);

        return $backend->get($key, $default);
    }

    /**
     * Set the given backend property value.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return bool
     */
    public function set($property, $value)
    {
        list($slug, $key) = explode('::', $property);

        $cachePath = $this->getCachePath();
        $cache = $this->getCache();
        $backend = $this->where('slug', $slug);

        if (isset($backend[$key])) {
            unset($backend[$key]);
        }

        $backend[$key] = $value;

        $backend = collect([$backend['basename'] => $backend]);

        $merged = $cache->merge($backend);
        $content = json_encode($merged->all(), JSON_PRETTY_PRINT);

        return $this->files->put($cachePath, $content);
    }

    /**
     * Get all enabled backends.
     *
     * @return Collection
     */
    public function enabled()
    {
        return $this->all()->where('enabled', true);
    }

    /**
     * Get all disabled backends.
     *
     * @return Collection
     */
    public function disabled()
    {
        return $this->all()->where('enabled', false);
    }

    /**
     * Check if specified backend is enabled.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isEnabled($slug)
    {
        $backend = $this->where('slug', $slug);

        return $backend['enabled'] === true;
    }

    /**
     * Check if specified backend is disabled.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isDisabled($slug)
    {
        $backend = $this->where('slug', $slug);

        return $backend['enabled'] === false;
    }

    /**
     * Enables the specified backend.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function enable($slug)
    {
        return $this->set($slug.'::enabled', true);
    }

    /**
     * Disables the specified backend.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function disable($slug)
    {
        return $this->set($slug.'::enabled', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Optimization Methods
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Update cached repository of backend information.
     *
     * @return bool
     */
    public function optimize()
    {
        $cachePath = $this->getCachePath();

        $cache = $this->getCache();
        $basenames = $this->getAllBasenames();
        $backends = collect();

        $basenames->each(function ($backend, $key) use ($backends, $cache) {
            $basename = collect(['basename' => $backend]);
            $temp = $basename->merge(collect($cache->get($backend)));
            $manifest = $temp->merge(collect($this->getManifest($backend)));

            $backends->put($backend, $manifest);
        });

        $backends->each(function ($backend) {
            $backend->put('id', crc32($backend->get('slug')));

            if (!$backend->has('enabled')) {
                $backend->put('enabled', config('backends.enabled', true));
            }

            if (!$backend->has('order')) {
                $backend->put('order', 9001);
            }

            return $backend;
        });

        $content = json_encode($backends->all(), JSON_PRETTY_PRINT);

        return $this->files->put($cachePath, $content);
    }

    /**
     * Get the contents of the cache file.
     *
     * @return Collection
     */
    private function getCache()
    {
        $cachePath = $this->getCachePath();

        if (!$this->files->exists($cachePath)) {
            $this->createCache();

            $this->optimize();
        }

        return collect(json_decode($this->files->get($cachePath), true));
    }

    /**
     * Create an empty instance of the cache file.
     *
     * @return Collection
     */
    private function createCache()
    {
        $cachePath = $this->getCachePath();
        $content = json_encode([], JSON_PRETTY_PRINT);

        $this->files->put($cachePath, $content);

        return collect(json_decode($content, true));
    }

    /**
     * Get the path to the cache file.
     *
     * @return string
     */
    private function getCachePath()
    {
        return storage_path('app/backends.json');
    }
}
