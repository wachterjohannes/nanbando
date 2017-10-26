<?php

namespace Nanbando\Tests\Unit\Process;

use Nanbando\Process\ProcessFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ProcessFactoryTest extends TestCase
{
    public function testCreate()
    {
        $processFactory = new ProcessFactory();
        $process = $processFactory->create('ssh');

        $this->assertInstanceOf(Process::class, $process);
        $this->assertEquals('ssh', $process->getCommandLine());
    }
}
