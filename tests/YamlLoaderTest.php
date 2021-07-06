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

    public function allowFirstEmptyYamlFile()
    {
        $filename1 = __DIR__ . '/fixture.empty.yaml';
        $filename2 = __DIR__ . '/fixture1.yaml';
        $filename3 = __DIR__ . '/fixture.empty.yaml';
        $filename4 = __DIR__ . '/fixture2.yaml';
        $filename5 = __DIR__ . '/fixture3.yaml';
        $config = new YamlLoader($filename1, $filename2, $filename3, $filename4, $filename5);

        $this->assertEquals('test', $config->require('notempty4'));
    }

    public function testWillThrowExceptionOnScalarYaml()
    {
        $filename1 = __DIR__ . '/fixture.scalar.yaml';

        $this->expectException(InvalidArgumentException::class);
        $config = new YamlLoader($filename1);
    }

}
