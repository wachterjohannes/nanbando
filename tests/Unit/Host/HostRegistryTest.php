<?php

namespace Nanbando\Tests\Unit\Host;

use Nanbando\Host\HostInterface;
use Nanbando\Host\HostRegistry;
use Nanbando\Host\Localhost;
use PHPUnit\Framework\TestCase;

class HostRegistryTest extends TestCase
{
    public function testDefaultHosts()
    {
        $hostRegistry = new HostRegistry();

        $this->assertInstanceOf(Localhost::class, $hostRegistry->get('localhost'));
    }

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
