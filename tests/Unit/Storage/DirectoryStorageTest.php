<?php

namespace Nanbando\Tests\Unit\Storage;

use Nanbando\Storage\DirectoryStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryStorageTest extends TestCase
{
    public function testUpload()
    {
        $filename = '20171117-233030';
        $localPath = '/local/' . $filename . '.tar.gz';

        $filesystem = $this->prophesize(Filesystem::class);
        $filesystem->copy($localPath, sprintf('/tmp/%s.tar.gz', $filename), true)->shouldBeCalled();

        $directoryStorage = new DirectoryStorage('/tmp', $filesystem->reveal());

        $this->assertEquals($directoryStorage, $directoryStorage->upload($filename, $localPath));
    }

    public function testDownload()
    {
        $filename = '20171117-233030';
        $localPath = '/local/' . $filename . '.tar.gz';

        $filesystem = $this->prophesize(Filesystem::class);
        $filesystem->copy(sprintf('/tmp/%s.tar.gz', $filename), $localPath, true)->shouldBeCalled();

        $directoryStorage = new DirectoryStorage('/tmp', $filesystem->reveal());

        $this->assertEquals($directoryStorage, $directoryStorage->download($filename, $localPath));
    }

    public function testExists()
    {
        $filename = '20171117-233030';

        $filesystem = $this->prophesize(Filesystem::class);
        $filesystem->exists(sprintf('/tmp/%s.tar.gz', $filename))->willReturn(false);

        $directoryStorage = new DirectoryStorage('/tmp', $filesystem->reveal());

        $this->assertFalse($directoryStorage->exists($filename));
    }

    public function testList()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $directoryStorage = new DirectoryStorage('/tmp', $filesystem->reveal());

        $this->assertEquals([], $directoryStorage->listFiles());
    }
}
