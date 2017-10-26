<?php

namespace Nanbando\Tests\Unit\Client;

use Nanbando\Client\SshClient;
use Nanbando\Host\HostInterface;
use Nanbando\Process\ProcessFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class SshClientTest extends TestCase
{
    public function testRun()
    {
        $processFactory = $this->prophesize(ProcessFactory::class);
        $host = $this->prophesize(HostInterface::class);

        $host->getDirectory()->willReturn('/home');
        $host->getUser()->willReturn('johannes');
        $host->getHostName()->willReturn('asapo.at');
        $host->getPort()->willReturn(22);

        $process = $this->prophesize(Process::class);
        $process->setTimeout(300)->shouldBeCalled()->willReturn($process->reveal());
        $process->setTty(true)->shouldBeCalled()->willReturn($process->reveal());
        $process->mustRun()->shouldBeCalled()->willReturn($process->reveal());
        $process->getOutput()->willReturn('hello');

        $processFactory->create('ssh -tt -p 22 johannes@asapo.at \'cd /home; echo "hello"\'')->willReturn($process->reveal());

        $client = new SshClient($processFactory->reveal(), $host->reveal(), 300);

        $this->assertEquals('hello', $client->run('echo "hello"'));
    }
}
