<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Backup\BackupArchiveFactory;
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
        BackupRunner $backup,
        BackupArchiveFactory $factory,
        InputInterface $input,
        BackupArchiveInterface $backupArchive
    ) {
        $this->beConstructedWith($backup, $factory);

        $input->bind(Argument::cetera())->willReturn(null);
        $input->isInteractive()->willReturn(null);
        $input->hasArgument(Argument::cetera())->willReturn(null);
        $input->validate()->willReturn(null);

        $factory->create()->willReturn($backupArchive);
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
        $input->getArgument('label')->willReturn(null);
        $input->getOption('message')->willReturn(null);

        $backupArchive->set('label', null)->shouldBeCalled();
        $backupArchive->set('message', null)->shouldBeCalled();

        $backup->run($backupArchive)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }

    public function it_should_run_backup_should_pass_tag(
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backup,
        BackupArchiveInterface $backupArchive
    ) {
        $input->getArgument('label')->willReturn('testlabel');
        $input->getOption('message')->willReturn(null);

        $backupArchive->set('label', 'testlabel')->shouldBeCalled();
        $backupArchive->set('message', null)->shouldBeCalled();

        $backup->run($backupArchive)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }

    public function it_should_run_backup_should_pass_message(
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backup,
        BackupArchiveInterface $backupArchive
    ) {
        $input->getArgument('label')->willReturn(null);
        $input->getOption('message')->willReturn('testmessage');

        $backupArchive->set('label', null)->shouldBeCalled();
        $backupArchive->set('message', 'testmessage')->shouldBeCalled();

        $backup->run($backupArchive)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }
}
