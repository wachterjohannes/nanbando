<?php

namespace Nanbando\Tests\Unit\Client;

use Nanbando\Client\CommandBuilder;
use PHPUnit\Framework\TestCase;

class CommandBuilderTest extends TestCase
{
    public function testToString()
    {
        $builder = new CommandBuilder('bin/nanbando', ['backup'], ['label' => 'test', 'verbose' => true]);

        $this->assertEquals('bin/nanbando backup --label="test" --verbose', $builder->__toString());
    }
}
