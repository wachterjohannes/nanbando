<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Backup\BackupArchiveFactory;
use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Backup\BackupRunner;
use Nanbando\Console\Command\DifferentialBackupCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DifferentialBackupCommandSpec extends ObjectBehavior
{
    public function let(
        BackupRunner $backupRunner,
        BackupArchiveFactory $factory,
        InputInterface $input
    ) {
        $this->beConstructedWith($backupRunner, $factory);

        $input->bind(Argument::cetera())->willReturn(null);
        $input->isInteractive()->willReturn(null);
        $input->hasArgument(Argument::cetera())->willReturn(null);
        $input->validate()->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DifferentialBackupCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_run_backup(
        BackupArchiveFactory $factory,
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backupRunner,
        BackupArchiveInterface $backupArchive
    ) {
        $factory->createDifferential('20180422-114300')->willReturn($backupArchive);

        $input->getArgument('parent')->willReturn('20180422-114300');
        $input->getArgument('label')->willReturn(null);
        $input->getOption('message')->willReturn(null);

        $backupArchive->set('label', null)->shouldBeCalled();
        $backupArchive->set('message', null)->shouldBeCalled();

        $backupRunner->run($backupArchive)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }

    public function it_should_run_backup_should_pass_tag(
        BackupArchiveFactory $factory,
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backupRunner,
        BackupArchiveInterface $backupArchive
    ) {
        $factory->createDifferential('20180422-114300')->willReturn($backupArchive);

        $input->getArgument('parent')->willReturn('20180422-114300');
        $input->getArgument('label')->willReturn('testlabel');
        $input->getOption('message')->willReturn(null);

        $backupArchive->set('label', 'testlabel')->shouldBeCalled();
        $backupArchive->set('message', null)->shouldBeCalled();

        $backupRunner->run($backupArchive)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }

    public function it_should_run_backup_should_pass_message(
        BackupArchiveFactory $factory,
        InputInterface $input,
        OutputInterface $output,
        BackupRunner $backupRunner,
        BackupArchiveInterface $backupArchive
    ) {
        $factory->createDifferential('20180422-114300')->willReturn($backupArchive);

        $input->getArgument('parent')->willReturn('20180422-114300');
        $input->getArgument('label')->willReturn(null);
        $input->getOption('message')->willReturn('testmessage');

        $backupArchive->set('label', null)->shouldBeCalled();
        $backupArchive->set('message', 'testmessage')->shouldBeCalled();

        $backupRunner->run($backupArchive)->shouldBeCalled()->willReturn($backupArchive);

        $this->run($input, $output);
    }
}
