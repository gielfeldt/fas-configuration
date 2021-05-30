<?php

namespace Fas\Configuration;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class YamlLoader extends ArrayConfiguration
{
    public function __construct(string ...$filenames)
    {
        $data = [];
        foreach ($filenames as $filename) {
            if (!file_exists($filename)) {
                throw new InvalidArgumentException("File '$filename' not found");
            }
            $data[] = Yaml::parseFile($filename);
        }
        $first = array_shift($data);
        $merged = array_replace_recursive($first, ...$data);
        parent::__construct($merged);
    }

    public static function loadWithOverrides($filename, $suffix = 'override')
    {
        $filenames = [$filename];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $overrideFilename = dirname($filename) . '/' . basename($filename, ".$extension") . ".$suffix.$extension";
        if (file_exists($overrideFilename)) {
            $filenames[] = $overrideFilename;
        }
        return new static(...$filenames);
    }
}
