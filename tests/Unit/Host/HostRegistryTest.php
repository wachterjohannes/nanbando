<?php

namespace Nanbando\Tests\Unit\Host;

use Nanbando\Host\HostInterface;
use Nanbando\Host\HostRegistry;
use Nanbando\Tests\TestCase;

class HostRegistryTest extends TestCase
{
    public function testRegister()
    {
        $hostRegistry = new HostRegistry();

        $host = $this->prophesize(HostInterface::class);
        $host->getName()->willReturn('asapo');

        $this->assertEquals($hostRegistry, $hostRegistry->register($host->reveal()));
    }

    public function testGet()
    {
        $hostRegistry = new HostRegistry();

        $host = $this->prophesize(HostInterface::class);
        $host->getName()->willReturn('asapo');

        $this->assertEquals($hostRegistry, $hostRegistry->register($host->reveal()));
        $this->assertEquals($host->reveal(), $hostRegistry->get('asapo'));
    }
}
