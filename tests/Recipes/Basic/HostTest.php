<?php

namespace Nanbando\Tests\Recipes\Basic;

use function Nanbando\host;
use Nanbando\Host\HostInterface;
use Nanbando\Host\HostRegistry;
use Nanbando\Nanbando;
use Nanbando\Tests\TestCase;

class HostTest extends TestCase
{
    public function testHost()
    {
        $host = host('asapo', 'asapo.at');

        $this->assertInstanceOf(HostInterface::class, $host);
        $this->assertEquals('asapo', $host->getName());
        $this->assertEquals('asapo.at', $host->getHostName());

        /** @var HostRegistry $hostRegistry */
        $hostRegistry = Nanbando::get()->getService(HostRegistry::class);

        $this->assertEquals($host, $hostRegistry->get('asapo'));
    }
}
