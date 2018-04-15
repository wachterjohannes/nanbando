<?php

namespace spec\Nanbando\Storage;

use Nanbando\Storage\DirectoryStorage;
use Nanbando\Storage\StorageInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryStorageSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem
    ) {
        $this->beConstructedWith('/tmp/storage', $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DirectoryStorage::class);
    }

    public function it_should_implement_storage_interface()
    {
        $this->shouldBeAnInstanceOf(StorageInterface::class);
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
}
