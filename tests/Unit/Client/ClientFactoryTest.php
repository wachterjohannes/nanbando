<?php

namespace Nanbando\Tests\Unit\Client;

use Nanbando\Client\ClientFactory;
use Nanbando\Client\ClientInterface;
use Nanbando\Client\SshClient;
use Nanbando\Host\HostInterface;
use Nanbando\Process\ProcessFactory;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    /**
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * @var HostInterface
     */
    private $host;

    protected function setUp()
    {
        parent::setUp();

        $this->processFactory = $this->prophesize(ProcessFactory::class);
        $this->host = $this->prophesize(HostInterface::class);

        $this->host->getName()->willReturn('asapo');
    }

    public function testCreate()
    {
        $clientFactory = new ClientFactory($this->processFactory->reveal());

        $client = $clientFactory->create($this->host->reveal());

        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(SshClient::class, $client);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('host');
        $property->setAccessible(true);

        $this->assertEquals($this->host->reveal(), $property->getValue($client));
    }

    public function testCreateTimeout()
    {
        $clientFactory = new ClientFactory($this->processFactory->reveal(), 600);

        $client = $clientFactory->create($this->host->reveal());

        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(SshClient::class, $client);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('timeout');
        $property->setAccessible(true);

        $this->assertEquals(600, $property->getValue($client));
    }
}
