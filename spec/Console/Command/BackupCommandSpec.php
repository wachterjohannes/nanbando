<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\BackupCommand;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_print_info_that_command_starts(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->run($input, $output);

        $output->writeln('Backup started')->shouldBeCalled();
    }
}
