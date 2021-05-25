<?php

namespace Fas\Configuration;

use Exception;

/**
 * Configuration key not found
 */
class NotFoundException extends Exception
{
    public function __construct(string $key, \Throwable $previous = null)
    {
        parent::__construct("Configuration key: '$key' not found", 0, $previous);
    }
}
