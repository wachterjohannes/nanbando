<?php

namespace spec\Nanbando\Restore;

use Nanbando\Restore\RestoreArchiveInterface;
use Nanbando\Restore\RestoreReader;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalStorage;
use Nanbando\Tar\TarFactory;
use PhpSpec\ObjectBehavior;
use splitbrain\PHPArchive\Tar;
use Symfony\Component\Filesystem\Filesystem;
use VirtualFileSystem\Loader;
use Webmozart\Assert\Assert;

class RestoreReaderSpec extends ObjectBehavior
{
    public function let(
        LocalStorage $localStorage,
        TarFactory $factory,
        Filesystem $filesystem
    ) {
        $this->beConstructedWith($localStorage, $factory, $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RestoreReader::class);
    }

    public function it_should_open_the_archive(
        LocalStorage $localStorage,
        TarFactory $factory,
        ArchiveInfo $archiveInfo,
        Tar $tar
    ) {
        $l = new Loader();
        $l->register();

        $fs = new \VirtualFileSystem\FileSystem();
        file_put_contents($fs->path('20180419-164300.json'), json_encode(['attribute1' => 'value1']));

        $localStorage->get('20180419-164300')->willReturn($archiveInfo);

        $archiveInfo->isFetched()->willReturn(true);
        $archiveInfo->getArchivePath()->willReturn($fs->path('20180419-164300.tar.gz'));
        $archiveInfo->getDatabasePath()->willReturn($fs->path('20180419-164300.json'));

        $factory->create()->willReturn($tar);

        $this->open('20180419-164300')->shouldBeRestoreArchive();
    }

    public function getMatchers(): array
    {
        return [
            'beRestoreArchive' => function (RestoreArchiveInterface $subject) {
                Assert::eq('value1', $subject->get('attribute1'));

                return true;
            },
        ];
    }
}
