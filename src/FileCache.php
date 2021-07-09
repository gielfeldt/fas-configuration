<?php

namespace Fas\Configuration;

use Exception;

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

    public static function save(string $filename, ConfigurationInterface $configuration, $preload = null)
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

        if ($preload) {
            self::savePreload($preload, $classFilename);
        }
    }

    private static function savePreload(string $filename, string $classFilename)
    {
        foreach (get_declared_classes() as $className) {
            if (strpos($className, 'ComposerAutoloader') === 0) {
                $classLoader = $className::getLoader();
                break;
            }
        }
        if (empty($classLoader)) {
            throw new Exception("Cannot locate class loader");
        }

        $files = [];
        $files[] = $classLoader->findFile(ConfigurationInterface::class);
        $files[] = $classLoader->findFile(GlobalConfiguration::class);
        $files[] = $classLoader->findFile(FileCache::class);
        $files[] = $classFilename;

        $code = "<?php\n";
        foreach ($files as $file) {
            $code .= 'require_once(' . var_export(realpath($file), true) . ");\n";
        }

        $tempFilename = tempnam(dirname($filename), 'fas-configuration');
        @chmod($tempFilename, 0666);
        file_put_contents($tempFilename, $code);
        @chmod($tempFilename, 0666);
        rename($tempFilename, $filename);
        @chmod($classFilename, 0666);
    }
}
