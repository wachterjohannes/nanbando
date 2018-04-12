<?php

namespace spec\Nanbando\Script;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\Script\DirectoryScript;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Finder\Finder;

class DirectoryScriptSpec extends ObjectBehavior
{
    public function let(
        Finder $finder,
         SectionOutputFormatter $sectionOutput
   ) {
        $sectionOutput->progressBar(Argument::any())->willReturn(new ProgressBar(new NullOutput()));

        $this->beConstructedWith($finder, __DIR__ . '/../../');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DirectoryScript::class);
    }

    public function it_should_add_all_files(
        Finder $finder,
        BackupArchiveInterface $backupArchive,
        SectionOutputFormatter $sectionOutput
    ) {
        $files = [
            new \SplFileInfo(__DIR__ . '/../../behat.yml.dist'),
            new \SplFileInfo(__DIR__ . '/../../phpspec.yml.dist'),
        ];

        $finder->getIterator()->willReturn(new \ArrayObject($files));
        $finder->count()->willReturn(2);

        $backupArchive->storeFile('behat.yml.dist', realpath(__DIR__ . '/../../behat.yml.dist'))->shouldBeCalled();
        $backupArchive->storeFile('phpspec.yml.dist', realpath(__DIR__ . '/../../phpspec.yml.dist'))->shouldBeCalled();

        $this->backup($backupArchive, $sectionOutput);
    }
}
