<?php

namespace spec\Nanbando\Storage;

use Nanbando\Console\OutputFormatter;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalStorage;
use Nanbando\Storage\RemoteStorage;
use Nanbando\Storage\StorageAdapterInterface;
use PhpSpec\ObjectBehavior;

class RemoteStorageSpec extends ObjectBehavior
{
    public function let(
        LocalStorage $localStorage,
        StorageAdapterInterface $storageAdapter,
        OutputFormatter $outputFormatter,
        SectionOutputFormatter $sectionOutputFormatter,
        ArchiveInfo $info1,
        ArchiveInfo $info2
    ) {
        $this->beConstructedWith($localStorage, $storageAdapter);

        $outputFormatter->section()->willReturn($sectionOutputFormatter);

        $info1->getName()->willReturn('20180415-204900');
        $info2->getName()->willReturn('20180415-205000');

        $info1->getArchivePath()->willReturn('/tmp/20180415-205000.tar.gz');
        $info1->getDatabasePath()->willReturn('/tmp/20180415-205000.json');

        $info1->getArchiveName()->willReturn('20180415-205000.tar.gz');
        $info1->getDatabaseName()->willReturn('20180415-205000.json');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RemoteStorage::class);
    }

    public function it_should_push_files(
        StorageAdapterInterface $storageAdapter,
        ArchiveInfo $info
    ) {
        $info->getArchivePath()->willReturn('/tmp/20180415-205000.tar.gz');
        $info->getDatabasePath()->willReturn('/tmp/20180415-205000.json');

        $storageAdapter->push('/tmp/20180415-205000.tar.gz')->shouldBeCalled();
        $storageAdapter->push('/tmp/20180415-205000.json')->shouldBeCalled();

        $this->push($info);
    }

    public function it_should_fetch_files(
        StorageAdapterInterface $storageAdapter,
        ArchiveInfo $info
    ) {
        $info->getArchivePath()->willReturn('/tmp/20180415-205000.tar.gz');
        $info->getArchiveName()->willReturn('20180415-205000.tar.gz');
        $info->getDatabasePath()->willReturn('/tmp/20180415-205000.json');
        $info->getDatabaseName()->willReturn('20180415-205000.json');

        $storageAdapter->fetch('20180415-205000.tar.gz', '/tmp/20180415-205000.tar.gz')->shouldBeCalled();
        $storageAdapter->fetch('20180415-205000.json', '/tmp/20180415-205000.json')->shouldBeCalled();

        $this->fetch($info);
    }

    public function it_should_return_a_list_of_files(
        LocalStorage $localStorage,
        StorageAdapterInterface $storageAdapter,
        ArchiveInfo $info1,
        ArchiveInfo $info2
    ) {
        $storageAdapter->listFiles()->willReturn(['20180415-204900', '20180415-205000']);

        $localStorage->get('20180415-204900')->willReturn($info1);
        $localStorage->get('20180415-205000')->willReturn($info2);

        $this->listFiles()->shouldBe([$info1, $info2]);
    }

    public function it_should_fetch_database(
        StorageAdapterInterface $storageAdapter,
        ArchiveInfo $info
    ) {
        $storageAdapter->fetch('20180415-205000.json', '/tmp/20180415-205000.json')->shouldBeCalled();

        $info->getDatabaseName()->willReturn('20180415-205000.json');
        $info->getDatabasePath()->willReturn('/tmp/20180415-205000.json');

        $this->fetchDatabase($info);
    }

    public function it_should_fetch_archive(
        StorageAdapterInterface $storageAdapter,
        ArchiveInfo $info
    ) {
        $storageAdapter->fetch('20180415-205000.tar.gz', '/tmp/20180415-205000.tar.gz')->shouldBeCalled();

        $info->getArchiveName()->willReturn('20180415-205000.tar.gz');
        $info->getArchivePath()->willReturn('/tmp/20180415-205000.tar.gz');

        $this->fetchArchive($info);
    }

    public function it_exists(
        StorageAdapterInterface $storageAdapter,
        ArchiveInfo $info
    ) {
        $storageAdapter->exists('/tmp/20180415-205000.tar.gz')->shouldBeCalled();

        $info->getArchivePath()->willReturn('/tmp/20180415-205000.tar.gz');

        $this->exists($info);
    }
}
