<?php

namespace Fas\Configuration;

class FileCache
{
    public static function load(string $filename): ?ConfigurationInterface
    {
        $loader = @include $filename;
        if (!is_array($loader)) {
            return null;
        }
        [$file, $class] = $loader;
        if (!class_exists($class, false)) {
            require_once $file;
        }
        return new $class();
    }

    public static function save(string $filename, ConfigurationInterface $configuration)
    {
        $exportedSettings = var_export($configuration->all(), true);
        $id = hash('sha256', $exportedSettings);
        ob_start();
        $class = "config_$id";
        include __DIR__ . '/CompiledConfiguration.tpl.php';
        $code = "<?php\n" . ob_get_contents() . "\n";
        ob_end_clean();

        $path = realpath(dirname($filename));
        $filename = basename($filename);

        $tempFilename = tempnam($path, 'config');
        $classFilename = "$path/$class.php";
        @chmod($tempFilename, 0666);
        file_put_contents($tempFilename, $code);
        @chmod($tempFilename, 0666);
        rename($tempFilename, $classFilename);
        @chmod($classFilename, 0666);

        $tempFilename = tempnam($path, 'config');
        $includerFilename = "$path/$filename";
        @chmod($tempFilename, 0666);
        file_put_contents($tempFilename, '<?php return ' . var_export([$classFilename, $class], true) . ';');
        @chmod($tempFilename, 0666);
        rename($tempFilename, $includerFilename);
        @chmod($includerFilename, 0666);
    }
}
