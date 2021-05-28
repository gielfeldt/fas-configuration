<?php

namespace Fas\Configuration\Tests;

use Fas\Configuration\NotFoundException;
use PHPUnit\Framework\TestCase;

abstract class GenericConfigurationTestBase extends TestCase
{
    abstract public function default_tests_dataprovider();

    /**
     * @dataProvider default_tests_dataprovider
     */
    public function testCanCheckForKeys($factory)
    {
        $config = $factory();

        $this->assertTrue($config->has('title'));
        $this->assertFalse($config->has('nothere'));
    }

    /**
     * @dataProvider default_tests_dataprovider
     */
    public function testCanGetWithDefault($factory)
    {
        $config = $factory();

        $this->assertEquals('a test', $config->get('title', 'fallback'));
        $this->assertEquals('fallback', $config->get('nothere', 'fallback'));
    }

    /**
     * @dataProvider default_tests_dataprovider
     */
    public function testCanRequireConfig($factory)
    {
        $config = $factory();

        $this->assertEquals('a test', $config->require('title'));
    }

    /**
     * @dataProvider default_tests_dataprovider
     */
    public function testCanRequireConfigFail($factory)
    {
        $config = $factory();

        $this->expectException(NotFoundException::class);
        $config->require('nothere');
    }

    /**
     * @dataProvider default_tests_dataprovider
     */
    public function testEmptyConfig($factory)
    {
        $config = $factory();

        $this->assertFalse($config->has('empty1'));
        $this->assertFalse($config->has('empty2'));
        $this->assertTrue($config->has('notempty1'));
        $this->assertTrue($config->has('notempty2'));
        $this->assertTrue($config->has('notempty3'));
        $this->assertTrue($config->has('notempty4'));
    }
}
