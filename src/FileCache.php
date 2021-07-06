<?php

namespace Fas\Configuration;

class FileCache
{
    public static function load(string $filename): ?ConfigurationInterface
    {
        $config = @include $filename;
        return $config ? new ArrayConfiguration($config) : null;
    }

    public static function save(string $filename, ConfigurationInterface $configuration)
    {
        $tempFilename = tempnam(dirname($filename), 'config');
        file_put_contents($tempFilename, '<?php return ' . var_export($configuration->all(), true) . ';');
        rename($tempFilename, $filename);
    }
}
