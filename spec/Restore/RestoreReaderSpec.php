<?php

namespace spec\Nanbando\Restore;

use Nanbando\Console\OutputFormatter;
use Nanbando\Restore\RestoreArchiveInterface;
use Nanbando\Restore\RestoreReader;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\Storage;
use Nanbando\Tar\TarFactory;
use PhpSpec\ObjectBehavior;
use splitbrain\PHPArchive\Tar;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Assert\Assert;

class RestoreReaderSpec extends ObjectBehavior
{
    public function let(
        Storage $storage,
        TarFactory $factory,
        Filesystem $filesystem,
        OutputFormatter $output
    ) {
        $this->beConstructedWith($storage, $factory, $filesystem, $output);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RestoreReader::class);
    }

    public function it_should_open_the_archive(
        Storage $storage,
        TarFactory $factory,
        ArchiveInfo $archiveInfo,
        Tar $tar
    ) {
        $storage->get('20180419-164300')->willReturn($archiveInfo);

        $archiveInfo->isFetched()->willReturn(true);
        $archiveInfo->openDatabase()->willReturn(new ParameterBag(['attribute1' => 'value1']));
        $archiveInfo->getArchivePath()->willReturn('/tmp/20180419-164300.tar.gz');

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
