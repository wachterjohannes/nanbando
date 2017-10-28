<?php

namespace Nanbando\Tests\Unit\Backup;

use Nanbando\Backup\BackupTaskCollection;
use Nanbando\Backup\Context\BackupContext;
use Nanbando\Console\Application;
use Nanbando\Filesystem\FilesystemFactory;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Task\TaskInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;

class BackupTaskCollectionTest extends TestCase
{
    public function testInvoke()
    {
        $application = $this->prophesize(Application::class);
        $application->getProcess()->willReturn(null);

        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystemFactory = $this->prophesize(FilesystemFactory::class);
        $filesystemFactory->create('test-label')->willReturn($filesystem->reveal());

        $filesystem->getName()->willReturn('20170101-175310')->shouldBeCalled();
        $filesystem->addContent(Argument::cetera())->shouldBeCalled();
        $filesystem->close()->shouldBeCalled();

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

        $input = $this->prophesize(InputInterface::class);
        $input->getArgument('label')->willReturn('test-label');
        $input->getOption('message')->willReturn('');

        $taskCollection = new BackupTaskCollection(
            $filesystemFactory->reveal(),
            $application->reveal(),
            new NullOutput(),
            $input->reveal()
        );

        $taskCollection->register('test', $task->reveal());

        $taskCollection->invoke();
    }
}
