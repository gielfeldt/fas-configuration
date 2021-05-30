<?php

namespace Fas\Configuration;

/**
 * Provide default values for a configuration
 *
 * Fallback to a second configuration, for missing values in the first configuration
 */
class DotNotation implements ConfigurationInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    private function traverse(array $value, array $parts, &$found)
    {
        $part = array_shift($parts);
        if (!array_key_exists($part, $value)) {
            $found = false;
            return null;
        }
        $value = $value[$part];
        if (!$parts) {
            $found = true;
            return $value;
        }
        return $this->traverse($value, $parts, $found);
    }

    public function has(string $key): bool
    {
        $parts = explode('.', $key);
        $key = array_shift($parts);
        if (!$this->configuration->has($key)) {
            return false;
        }
        if (!$parts) {
            return true;
        }

        $value = $this->configuration->require($key);
        $found = false;
        $this->traverse($value, $parts, $found);
        return $found;
    }

    public function get(string $key, $default)
    {
        $parts = explode('.', $key);
        $key = array_shift($parts);
        if (!$this->configuration->has($key)) {
            return $default;
        }
        $value = $this->configuration->require($key);
        if (!$parts) {
            return $value;
        }
        $found = false;
        $value = $this->traverse($value, $parts, $found);
        return $found ? $value : $default;
    }

    public function require(string $key)
    {
        $parts = explode('.', $key);
        $keyPart = array_shift($parts);
        $value = $this->configuration->require($keyPart);
        if (!$parts) {
            return $value;
        }
        $found = false;
        $value = $this->traverse($value, $parts, $found);
        if (!$found) {
            throw new NotFoundException($key);
        }
        return $value;
    }

    public function all(): array
    {
        $result = [];
        foreach (array_keys($this->configuration->all()) as $key) {
            $result += $this->expand($key);
        }
        return $result;
    }

    private function expand($key)
    {
        $expanded = [];
        $expanded[$key] = $value = $this->require($key);
        if (!is_array($value)) {
            return $expanded;
        }
        foreach (array_keys($value) as $subKey) {
            $expanded += $this->expand("$key.$subKey");
        }
        return $expanded;
    }
}
