<?php

namespace Fas\Configuration;

/**
 * Provide an array as a ConfigurationInterface
 */
class ArrayConfiguration implements ConfigurationInterface
{
    protected array $settings;

    /**
     * @param $settings array The array to use as configuration
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @inheritdoc
     */
    public function has(string $key): bool
    {
        return isset($this->settings[$key]);
    }

    /**
     * @inheritdoc
     */
    public function get(string $key, $default)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function require(string $key)
    {
        if (!$this->has($key)) {
            throw new NotFoundException($key);
        }
        return $this->settings[$key];
    }

    public function all(): array
    {
        return $this->settings;
    }
}
