<?php

namespace Fas\Configuration\Tests;

use Fas\Configuration\ArrayConfiguration;
use Fas\Configuration\GlobalConfiguration;
use PHPUnit\Framework\TestCase;

class GlobalConfigurationTest extends TestCase
{
    public function testCanSetAndGetGlobalConfiguration()
    {
        $original = new ArrayConfiguration([1, 2, 3]);

        GlobalConfiguration::setConfiguration($original);

        $retrieved = GlobalConfiguration::getConfiguration();

        $this->assertEquals($original->all(), $retrieved->all());
    }
}
