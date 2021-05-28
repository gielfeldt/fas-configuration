<?php

namespace Fas\Configuration\Tests;

use Fas\Configuration\ArrayConfiguration;
use Fas\Configuration\DotNotation;
use Fas\Configuration\NotFoundException;

class DotNotationTest extends GenericConfigurationTestBase
{
    public function default_tests_dataprovider()
    {
        $factory = function () {
            return new DotNotation(new ArrayConfiguration([
                'title' => 'a test',
                'empty1' => null,
                'notempty1' => '0',
                'notempty2' => 0,
                'notempty3' => [],
                'notempty4' => '',
            ]));
        };
        return [
            [$factory]
        ];
    }

    public function testCanRequireNestedLeafValueDirectly()
    {
        $config = new DotNotation(new ArrayConfiguration([
            'level1' => [
                'level2' => [
                    'level3' => 'value1',
                    'level3_1' => 'value2',
                ],
            ],
        ]));

        $this->assertEquals('value1', $config->require('level1.level2.level3'));
        $this->assertEquals('value2', $config->require('level1.level2.level3_1'));
    }

    public function testCanRequireNestedNonLeafValueDirectly()
    {
        $config = new DotNotation(new ArrayConfiguration([
            'level1' => [
                'level2' => [
                    'level3' => 'value1',
                    'level3_1' => 'value2',
                ],
            ],
        ]));

        $this->assertEquals([
            'level3' => 'value1',
            'level3_1' => 'value2',
        ], $config->require('level1.level2'));
    }

    public function testCanRequireConfigFailOnNestedKey()
    {
        $config = new DotNotation(new ArrayConfiguration([
            'level1' => [
                'level2' => null,
            ],
        ]));

        $this->expectException(NotFoundException::class);
        $config->require('level1.level3');
    }

    public function testCanCheckNestedKey()
    {
        $config = new DotNotation(new ArrayConfiguration([
            'level1' => [
                'level2' => null,
            ],
        ]));

        $this->assertTrue($config->has('level1'));
        $this->assertTrue($config->has('level1.level2'));
        $this->assertFalse($config->has('level1.level3'));
    }

    public function testCanGetNestedLeafValueDirectly()
    {
        $config = new DotNotation(new ArrayConfiguration([
            'level1' => [
                'level2' => [
                    'level3' => 'value1',
                    'level3_1' => 'value2',
                ],
            ],
        ]));

        $this->assertEquals([
            'level2' => [
                'level3' => 'value1',
                'level3_1' => 'value2',
            ],
        ], $config->get('level1', 'nope'));

        $this->assertEquals([
            'level3' => 'value1',
            'level3_1' => 'value2',
        ], $config->get('level1.level2', 'nope'));

        $this->assertEquals('value1', $config->get('level1.level2.level3', 'nope'));
        $this->assertEquals('value2', $config->get('level1.level2.level3_1', 'nope'));
        $this->assertEquals('nope', $config->get('level1.level2.level3_3', 'nope'));
    }

    public function testCanGetAll()
    {
        $config = new DotNotation(new ArrayConfiguration([
            'level1' => [
                'level2' => [
                    'level3' => 'value1',
                    'level3_1' => 'value2',
                ],
            ],
        ]));

        $this->assertEquals([
            'level1' => [
                'level2' => [
                    'level3' => 'value1',
                    'level3_1' => 'value2',
                ],
            ],
            'level1.level2' => [
                'level3' => 'value1',
                'level3_1' => 'value2',
            ],
            'level1.level2.level3' => 'value1',
            'level1.level2.level3_1' => 'value2',
        ], $config->all());
    }
}
