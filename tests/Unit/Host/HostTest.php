<?php

namespace Nanbando\Tests\Unit\Host;

use Nanbando\Host\Host;
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    public function testConstruct()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertEquals('asapo', $host->getName());
    }

    public function testHostName()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertEquals('asapo.at', $host->getHostName());
    }

    public function testUser()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertEquals($host, $host->setUser('asapo'));
        $this->assertEquals('asapo', $host->getUser());
    }

    public function testDirectory()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertEquals($host, $host->setDirectory('/var/www/asapo.at'));
        $this->assertEquals('/var/www/asapo.at', $host->getDirectory());
    }

    public function testPort()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertEquals($host, $host->setPort(222));
        $this->assertEquals(222, $host->getPort());
    }

    public function testPortDefault()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertEquals(22, $host->getPort());
    }

    public function testIsLocalhost()
    {
        $host = new Host('localhost', 'localhost');

        $this->assertTrue($host->isLocalhost());
    }

    public function testIsNotLocalhost()
    {
        $host = new Host('asapo', 'asapo.at');

        $this->assertFalse($host->isLocalhost());
    }
}
