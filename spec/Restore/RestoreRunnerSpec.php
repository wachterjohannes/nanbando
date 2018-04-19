<?php

namespace spec\Nanbando\Restore;

use Nanbando\Clock\ClockInterface;
use Nanbando\Console\OutputFormatter;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\Restore\RestoreArchiveInterface;
use Nanbando\Restore\RestoreReader;
use Nanbando\Restore\RestoreRunner;
use Nanbando\Script\ScriptInterface;
use Nanbando\Script\ScriptRegistry;
use Nanbando\TempFileManager\TempFileManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestoreRunnerSpec extends ObjectBehavior
{
    public function let(
        ClockInterface $clock,
        ScriptRegistry $scriptRegistry,
        RestoreReader $restoreReader,
        TempFileManagerInterface $tempFileManager,
        OutputFormatter $output,
        \DateTimeImmutable $dateTime
    ) {
        $clock->getDateTime()->willReturn($dateTime);

        $this->beConstructedWith($clock, $scriptRegistry, $restoreReader, $tempFileManager, $output);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RestoreRunner::class);
    }

    public function it_should_get_restore_scripts_and_run_them(
        ClockInterface $clock,
        ScriptRegistry $scriptRegistry,
        RestoreReader $restoreReader,
        TempFileManagerInterface $tempFileManager,
        OutputFormatter $output,
        ScriptInterface $script1,
        ScriptInterface $script2,
        SectionOutputFormatter $sectionFormatter,
        RestoreArchiveInterface $restoreArchive
    ) {
        $output->headline(Argument::cetera())->shouldBeCalled();
        $output->list(Argument::cetera())->shouldBeCalled();
        $output->info(Argument::cetera())->shouldBeCalled();
        $output->section()->willReturn($sectionFormatter);

        $scriptRegistry->get()->willReturn(['script1' => $script1, 'script2' => $script2]);

        $script1->restore(Argument::type(RestoreArchiveInterface::class), $sectionFormatter)->shouldBeCalled();
        $script2->restore(Argument::type(RestoreArchiveInterface::class), $sectionFormatter)->shouldBeCalled();

        $restoreReader->open('20180419-165900')->willReturn($restoreArchive);

        $tempFileManager->cleanup($sectionFormatter)->shouldBeCalled();

        $this->run('20180419-165900');
    }
}
