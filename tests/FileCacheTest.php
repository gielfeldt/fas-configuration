<?php

namespace Fas\Configuration\Tests;

use Fas\Configuration\ArrayConfiguration;
use Fas\Configuration\FileCache;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    public function testCanHandleMissingCacheFile()
    {
        $filename = tempnam(sys_get_temp_dir(), 'fas-configuration');
        unlink($filename);
        $configuration = FileCache::load($filename);

        $this->assertNull($configuration);
    }


    public function testCanSaveAndLoadConfiguration()
    {
        $configuration = new ArrayConfiguration([
            'test' => 1,
            'best' => 2,
        ]);
        $filename = tempnam(sys_get_temp_dir(), 'fas-configuration');

        FileCache::save($filename, $configuration);

        $loaded = FileCache::load($filename);

        $this->assertEquals($configuration->all(), $loaded->all());
    }
}
