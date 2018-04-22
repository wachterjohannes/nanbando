<?php

namespace Nanbando\Tests\Behat;

use Webmozart\Assert\Assert as WebmozartAssert;

class Assert extends WebmozartAssert
{
    public static function fileNotExists($value, $message = '')
    {
        static::string($value);

        if (file_exists($value)) {
            static::reportInvalidArgument(
                sprintf(
                    $message ?: 'The file %s should not exist.',
                    static::valueToString($value)
                )
            );
        }
    }
}
