<?php

namespace spec\Nanbando\Storage;

use Nanbando\Storage\DirectoryStorageAdapter;
use Nanbando\Storage\StorageAdapterInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryStorageAdapterSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem
    ) {
        $this->beConstructedWith('/tmp/storage', $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DirectoryStorageAdapter::class);
    }

    public function it_should_implement_storage_interface()
    {
        $this->shouldBeAnInstanceOf(StorageAdapterInterface::class);
    }

    public function it_should_copy_file_to_storage(
        Filesystem $filesystem
    ) {
        $filePath = '/tmp/var/backups/20180412-202357.tar.gz';

        $filesystem->copy($filePath, '/tmp/storage/20180412-202357.tar.gz')->shouldBeCalled();

        $this->push($filePath);
    }

    public function it_should_check_if_file_exists(
        Filesystem $filesystem
    ) {
        $filePath = '/tmp/var/backups/20180412-202357.tar.gz';

        $filesystem->exists('/tmp/storage/20180412-202357.tar.gz')->shouldBeCalled()->willReturn(true);

        $this->exists($filePath)->shouldEqual(true);
    }

    public function it_should_list_files()
    {
        $this->listFiles()->shouldEqual([]);
    }

    public function it_should_copy_file(
        Filesystem $filesystem
    ) {
        $filesystem->copy('/tmp/storage/20180412-202357.tar.gz', '/tmp/var/backups/20180412-202357.tar.gz', true)
            ->shouldBeCalled();

        $this->fetch('20180412-202357.tar.gz', '/tmp/var/backups/20180412-202357.tar.gz');
    }
}
