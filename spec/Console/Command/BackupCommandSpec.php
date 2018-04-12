<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Backup\BackupRunner;
use Nanbando\Console\Command\BackupCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommandSpec extends ObjectBehavior
{
    public function let(
        BackupRunner $backup
    ) {
        $this->beConstructedWith($backup);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_run_backup(
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backup,
        BackupArchiveInterface $backupArchive
    ) {
        $backup->run(null, null)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }

    public function it_should_run_backup_should_pass_tag(
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backup,
        BackupArchiveInterface $backupArchive
    ) {
        $input->bind(Argument::cetera())->shouldBeCalled();
        $input->isInteractive()->shouldBeCalled();
        $input->hasArgument(Argument::cetera())->shouldBeCalled();
        $input->validate()->shouldBeCalled();

        $input->getArgument('tag')->willReturn('testtag');
        $input->getOption('message')->willReturn(null);

        $backup->run('testtag', null)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }

    public function it_should_run_backup_should_pass_message(
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backup,
        BackupArchiveInterface $backupArchive
    ) {
        $input->bind(Argument::cetera())->shouldBeCalled();
        $input->isInteractive()->shouldBeCalled();
        $input->hasArgument(Argument::cetera())->shouldBeCalled();
        $input->validate()->shouldBeCalled();

        $input->getArgument('tag')->willReturn(null);
        $input->getOption('message')->willReturn('testmessage');

        $backup->run(null, 'testmessage')->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }
}
