<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\PushToCommand;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\RemoteStorage;
use Nanbando\Storage\StorageRegistry;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushToCommandSpec extends ObjectBehavior
{
    public function let(
        StorageRegistry $registry,
        OutputFormatter $outputFormatter,
        RemoteStorage $storage,
        InputInterface $input
    ) {
        $this->beConstructedWith($registry, $outputFormatter);

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
        OutputFormatter $outputFormatter,
        RemoteStorage $storage
    ) {
        $input->getArgument('storage')->willReturn('test');

        $storage->push($outputFormatter)->shouldBeCalled();

        $this->run($input, $output);
    }
}
