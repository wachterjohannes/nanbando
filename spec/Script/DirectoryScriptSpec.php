<?php

namespace spec\Nanbando\Script;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\File\FileHasher;
use Nanbando\Restore\RestoreArchiveInterface;
use Nanbando\Script\DirectoryScript;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DirectoryScriptSpec extends ObjectBehavior
{
    public function let(
        Finder $finder,
        SectionOutputFormatter $sectionOutput,
        Filesystem $filesystem,
        FileHasher $fileHasher
    ) {
        $sectionOutput->progressBar(Argument::any())->willReturn(new ProgressBar(new NullOutput()));

        $this->beConstructedWith($finder, __DIR__ . '/../../', $filesystem, $fileHasher);
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

    public function it_should_fetch_all_files(
        RestoreArchiveInterface $restoreArchive,
        SectionOutputFormatter $sectionOutput,
        Filesystem $filesystem
    ) {
        $restoreArchive->getMetadata()->willReturn(
            [
                'behat.yml.dist' => [
                    'hash' => '123',
                ],
                'phpspec.yml.dist' => [
                    'hash' => '456',
                ],
            ]
        );

        $filesystem->exists(realpath(__DIR__ . '/../../behat.yml.dist'))->willReturn(false);
        $filesystem->exists(realpath(__DIR__ . '/../../phpspec.yml.dist'))->willReturn(false);

        $restoreArchive->fetchFile('behat.yml.dist', realpath(__DIR__ . '/../../behat.yml.dist'))->shouldBeCalled();
        $restoreArchive->fetchFile('phpspec.yml.dist', realpath(__DIR__ . '/../../phpspec.yml.dist'))->shouldBeCalled();

        $this->restore($restoreArchive, $sectionOutput);
    }

    public function it_should_fetch_all_files_and_ignore_if_same(
        RestoreArchiveInterface $restoreArchive,
        SectionOutputFormatter $sectionOutput,
        Filesystem $filesystem,
        FileHasher $fileHasher
    ) {
        $restoreArchive->getMetadata()->willReturn(
            [
                'behat.yml.dist' => [
                    'hash' => '123',
                ],
            ]
        );

        $filesystem->exists(realpath(__DIR__ . '/../../behat.yml.dist'))->willReturn(true);
        $fileHasher->hash(realpath(__DIR__ . '/../../behat.yml.dist'))->willReturn('123');

        $restoreArchive->fetchFile('behat.yml.dist', realpath(__DIR__ . '/../../behat.yml.dist'))->shouldNotBeCalled();

        $this->restore($restoreArchive, $sectionOutput);
    }

    public function it_should_fetch_all_files_and_overwrite(
        RestoreArchiveInterface $restoreArchive,
        SectionOutputFormatter $sectionOutput,
        Filesystem $filesystem,
        FileHasher $fileHasher
    ) {
        $restoreArchive->getMetadata()->willReturn(
            [
                'behat.yml.dist' => [
                    'hash' => '123',
                ],
            ]
        );

        $filesystem->exists(realpath(__DIR__ . '/../../behat.yml.dist'))->willReturn(true);
        $fileHasher->hash(realpath(__DIR__ . '/../../behat.yml.dist'))->willReturn('456');

        $filesystem->remove(realpath(__DIR__ . '/../../behat.yml.dist'))->shouldBeCalled();
        $restoreArchive->fetchFile('behat.yml.dist', realpath(__DIR__ . '/../../behat.yml.dist'))->shouldBeCalled();

        $this->restore($restoreArchive, $sectionOutput);
    }
}
