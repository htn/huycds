<?php

namespace Huycds\Backends\Contracts;

interface Repository
{
    /**
     * Get all backend manifest properties and store
     * in the respective container.
     *
     * @return bool
     */
    public function optimize();

    /**
     * Get all backends.
     *
     * @return Collection
     */
    public function all();

    /**
     * Get all backend slugs.
     *
     * @return Collection
     */
    public function slugs();

    /**
     * Get backends based on where clause.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Collection
     */
    public function where($key, $value);

    /**
     * Sort backends by given key in ascending order.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function sortBy($key);

    /**
     * Sort backends by given key in ascending order.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function sortByDesc($key);

    /**
     * Determines if the given backend exists.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function exists($slug);

    /**
     * Returns a count of all backends.
     *
     * @return int
     */
    public function count();

    /**
     * Returns the backends defined manifest properties.
     *
     * @param string $slug
     *
     * @return Collection
     */
    public function getManifest($slug);

    /**
     * Returns the given backend property.
     *
     * @param string     $property
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function get($property, $default = null);

    /**
     * Set the given backend property value.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return bool
     */
    public function set($property, $value);

    /**
     * Get all enabled backends.
     *
     * @return Collection
     */
    public function enabled();

    /**
     * Get all disabled backends.
     *
     * @return Collection
     */
    public function disabled();

    /**
     * Determines if the specified backend is enabled.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isEnabled($slug);

    /**
     * Determines if the specified backend is disabled.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isDisabled($slug);

    /**
     * Enables the specified backend.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function enable($slug);

    /**
     * Disables the specified backend.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function disable($slug);
}
