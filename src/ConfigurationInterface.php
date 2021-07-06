<?php

namespace Fas\Configuration;

/**
 * Immutable configuration interface
 */
interface ConfigurationInterface
{
    /**
     * Check if key exists in configuration
     *
     * @param $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get configuration by key
     *
     * @param $key     string
     * @param $default mixed Fallback value, used if key was not found
     *
     * @return mixed The configuration value of $key if found, else $default
     */
    public function get(string $key, $default);

    /**
     * Require configuration by key
     *
     * @param $key
     *
     * @return mixed The configuration value
     *
     * @throws NotFoundException
     */
    public function require(string $key);

    /**
     * Return all configuration variables available.
     *
     * @return array
     */
    public function all(): array;
}
