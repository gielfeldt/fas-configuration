<?php

namespace Fas\Configuration;

class FileCache
{
    public static function load(string $filename): ?ConfigurationInterface
    {
        $config = @include $filename;
        return $config ? new ArrayConfiguration($config) : null;
    }

    public static function use(?string $filename, callable $callback): ConfigurationInterface
    {
        if (!$filename) {
            return $callback();
        }

        if (file_exists($filename)) {
            $configuration = new ArrayConfiguration(require $filename);
        } else {
            $configuration = $callback();
            self::save($filename, $configuration);
        }
        return $configuration;
    }

    public static function save(string $filename, ConfigurationInterface $configuration)
    {
        $tempFilename = tempnam(dirname($filename), 'config');
        file_put_contents($tempFilename, '<?php return ' . var_export($configuration->all(), true) . ';');
        rename($tempFilename, $filename);
    }
}
