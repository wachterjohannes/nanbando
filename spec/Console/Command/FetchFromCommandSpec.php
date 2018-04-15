<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\FetchFromCommand;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\RemoteStorage;
use Nanbando\Storage\StorageRegistry;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchFromCommandSpec extends ObjectBehavior
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
        $this->shouldHaveType(FetchFromCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_upload_not_existing_files(
        InputInterface $input,
        OutputInterface $output,
        RemoteStorage $storage,
        OutputFormatter $outputFormatter
    ) {
        $input->getArgument('storage')->willReturn('test');

        $storage->fetch($outputFormatter)->shouldBeCalled();

        $this->run($input, $output);
    }
}
