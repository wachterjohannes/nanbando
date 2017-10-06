<?php

namespace Nanbando\Tests\Unit\Host;

use Nanbando\Host\Host;
use Nanbando\Tests\TestCase;

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
}
