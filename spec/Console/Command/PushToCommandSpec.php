<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\PushToCommand;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\StorageInterface;
use Nanbando\Storage\StorageRegistry;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PushToCommandSpec extends ObjectBehavior
{
    public function let(
        Finder $finder,
        StorageRegistry $registry,
        OutputFormatter $output,
        StorageInterface $storage,
        InputInterface $input
    ) {
        $this->beConstructedWith($finder, $registry, $output);

        $registry->get('test')->willReturn($storage);

        $input->bind(Argument::cetera())->willReturn(null);
        $input->isInteractive()->willReturn(null);
        $input->hasArgument(Argument::cetera())->willReturn(null);
        $input->validate()->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PushToCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_upload_not_existing_files(
        InputInterface $input,
        OutputInterface $output,
        Finder $finder,
        StorageInterface $storage,
        SplFileInfo $file1,
        SplFileInfo $file2
    ) {
        $input->getArgument('storage')->willReturn('test');

        $file1->getPathname()->willReturn('/var/backups/20180412-202357.tar.gz');
        $file2->getPathname()->willReturn('/var/backups/20180413-202357.tar.gz');

        $array = new \ArrayObject([$file1->getWrappedObject(), $file2->getWrappedObject()]);

        $finder->getIterator()->willReturn($array);

        $storage->exists('/var/backups/20180412-202357.tar.gz')->willReturn(false);
        $storage->exists('/var/backups/20180413-202357.tar.gz')->willReturn(true);

        $storage->push('/var/backups/20180412-202357.tar.gz')->shouldBeCalled();
        $storage->push('/var/backups/20180413-202357.tar.gz')->shouldNotBeCalled();

        $this->run($input, $output);
    }
}
