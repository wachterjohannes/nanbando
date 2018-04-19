<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\RestoreCommand;
use Nanbando\Restore\RestoreRunner;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreCommandSpec extends ObjectBehavior
{
    public function let(
        RestoreRunner $restoreRunner,
        InputInterface $input
    ) {
        $this->beConstructedWith($restoreRunner);

        $input->bind(Argument::cetera())->willReturn(null);
        $input->isInteractive()->willReturn(null);
        $input->hasArgument(Argument::cetera())->willReturn(null);
        $input->validate()->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RestoreCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_run_restore(
        InputInterface $input,
        OutputInterface $output,
        RestoreRunner $restoreRunner
    ) {
        $input->getArgument('file')->willReturn('20180419-170300');

        $restoreRunner->run('20180419-170300')->shouldBeCalled();

        $this->run($input, $output);
    }
}
