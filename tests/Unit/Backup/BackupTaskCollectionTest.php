<?php

namespace Nanbando\Tests\Unit\Backup;

use Nanbando\Backup\BackupTaskCollection;
use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemFactory;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Task\TaskInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class BackupTaskCollectionTest extends TestCase
{
    public function testInvoke()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystemFactory = $this->prophesize(FilesystemFactory::class);
        $filesystemFactory->create()->willReturn($filesystem->reveal());

        $task = $this->prophesize(TaskInterface::class);
        $task->getParameter()->willReturn([]);
        $task->setParameter(
            Argument::that(
                function ($args) {
                    $this->assertInstanceOf(BackupContext::class, $args[0]);
                }
            )
        )->willReturn([]);

        $task->before(Argument::type('callable'), Argument::type('array'))->shouldBeCalled();
        $task->after(Argument::type('callable'), Argument::type('array'))->shouldBeCalled();
        $task->invoke()->shouldBeCalled();

        $taskCollection = new BackupTaskCollection($filesystemFactory->reveal());

        $taskCollection->register('test', $task->reveal());

        $taskCollection->invoke();
    }
}