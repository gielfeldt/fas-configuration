<?php

namespace Fas\Configuration;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class YamlLoader extends ArrayConfiguration
{
    public function __construct(string ...$filenames)
    {
        $allData = [];
        foreach ($filenames as $filename) {
            if (!file_exists($filename)) {
                throw new InvalidArgumentException("File '$filename' not found");
            }
            $data = Yaml::parseFile($filename) ?? [];
            if (!is_array($data)) {
                throw new InvalidArgumentException("$filename cannot only contain a scalar value");
            }
            $allData[] = $data;
        }
        $first = array_shift($allData);
        $merged = array_replace_recursive($first, ...$allData);
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
