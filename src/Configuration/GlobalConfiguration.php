<?php

namespace Fas\Configuration;

class GlobalConfiguration
{
    private static ConfigurationInterface $configuration;

    public static function setConfiguration(ConfigurationInterface $configuration)
    {
        static::$configuration = $configuration;
    }

    public static function getConfiguration(): ConfigurationInterface
    {
        return static::$configuration;
    }
}
