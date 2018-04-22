<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Backup\BackupRunner;
use Nanbando\Backup\BackupWriter;
use Nanbando\Clock\ClockInterface;
use Nanbando\Console\OutputFormatter;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\Script\ScriptInterface;
use Nanbando\Script\ScriptRegistry;
use Nanbando\TempFileManager\TempFileManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BackupRunnerSpec extends ObjectBehavior
{
    public function let(
        ClockInterface $clock,
        ScriptRegistry $scriptRegistry,
        BackupWriter $backupWriter,
        TempFileManagerInterface $tempFileManager,
        OutputFormatter $output,
        \DateTimeImmutable $dateTime
    ) {
        $clock->getDateTime()->willReturn($dateTime);

        $this->beConstructedWith($clock, $scriptRegistry, $backupWriter, $tempFileManager, $output);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupRunner::class);
    }

    public function it_should_get_backup_scripts_and_run_them(
        ScriptRegistry $scriptRegistry,
        BackupWriter $backupWriter,
        TempFileManagerInterface $tempFileManager,
        OutputFormatter $output,
        ScriptInterface $script1,
        ScriptInterface $script2,
        SectionOutputFormatter $sectionFormatter,
        BackupArchiveInterface $backupArchive
    ) {
        $output->headline(Argument::cetera())->shouldBeCalled();
        $output->list(Argument::cetera())->shouldBeCalled();
        $output->info(Argument::cetera())->shouldBeCalled();
        $output->section()->willReturn($sectionFormatter);

        $scriptRegistry->get()->willReturn(['script1' => $script1, 'script2' => $script2]);

        $script1->backup(Argument::type(BackupArchiveInterface::class), $sectionFormatter)->shouldBeCalled();
        $script2->backup(Argument::type(BackupArchiveInterface::class), $sectionFormatter)->shouldBeCalled();

        $backupWriter->write(Argument::type(\DateTimeImmutable::class), $backupArchive, $sectionFormatter)
            ->shouldBeCalled()
            ->willReturn('20180101-010100');

        $tempFileManager->cleanup($sectionFormatter)->shouldBeCalled();

        $backupArchive->set(Argument::cetera())->shouldBeCalled();
        $backupArchive->getWithDefault('label', '')->willReturn('');
        $backupArchive->getWithDefault('message', '')->willReturn('');

        $this->run($backupArchive)->shouldBe($backupArchive);
    }
}
