<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\FetchFromCommand;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\StorageInterface;
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
        OutputFormatter $output,
        StorageInterface $storage,
        InputInterface $input
    ) {
        $this->beConstructedWith('/var/backups', $registry, $output);

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
        StorageInterface $storage
    ) {
        $input->getArgument('storage')->willReturn('test');

        $storage->listFiles()->willReturn(['20180412-202357.tar.gz', '20180413-202357.tar.gz']);

        $storage->fetch('20180412-202357.tar.gz', '/var/backups/20180412-202357.tar.gz')->shouldBeCalled();
        $storage->fetch('20180413-202357.tar.gz', '/var/backups/20180413-202357.tar.gz')->shouldBeCalled();

        $this->run($input, $output);
    }
}
