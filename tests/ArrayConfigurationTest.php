<?php

namespace Fas\Configuration\Tests;

use Fas\Configuration\ArrayConfiguration;

class ArrayConfigurationTest extends GenericConfigurationTestBase
{
    public function default_tests_dataprovider()
    {
        $factory = function () {
            return new ArrayConfiguration([
                'title' => 'a test',
                'empty1' => null,
                'notempty1' => '0',
                'notempty2' => 0,
                'notempty3' => [],
                'notempty4' => '',
            ]);
        };
        return [
            [$factory]
        ];
    }

    /**
     * @dataProvider default_tests_dataprovider
     */
    public function testCanGetAll($factory)
    {
        $config = $factory();

        $this->assertEquals([
            'title' => 'a test',
            'empty1' => null,
            'notempty1' => '0',
            'notempty2' => 0,
            'notempty3' => [],
            'notempty4' => '',
        ], $config->all());
    }
}
