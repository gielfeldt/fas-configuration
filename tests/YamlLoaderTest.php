<?php

namespace Fas\Configuration\Tests;

use Fas\Configuration\YamlLoader;
use InvalidArgumentException;

class YamlLoaderTest extends GenericConfigurationTestBase
{
    public function default_tests_dataprovider()
    {
        $factories[] = [function () {
            $filename = __DIR__ . '/fixture1.yaml';
            return new YamlLoader($filename);
        }];
        $factories[] = [function () {
            $filename1 = __DIR__ . '/fixture2.yaml';
            $filename2 = __DIR__ . '/fixture3.yaml';
            return new YamlLoader($filename1, $filename2);
        }];
        $factories[] = [function () {
            $filename = __DIR__ . '/fixture2.yaml';
            return YamlLoader::loadWithOverrides($filename);
        }];
        return $factories;
    }

    public function testWillThrowExceptionOnMissingConfigurationFile()
    {
        $filename1 = __DIR__ . '/fixture1.yaml';
        $filename2 = __DIR__ . '/fixture2.yaml';
        $filename2 = __DIR__ . '/fixture3.yaml';
        $filename2 = __DIR__ . '/fixture4.yaml';

        $this->expectException(InvalidArgumentException::class);
        $config = new YamlLoader($filename1, $filename2);
    }
}
