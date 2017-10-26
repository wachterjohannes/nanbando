<?php

namespace Nanbando\Tests\Unit\Filesystem;

use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Filesystem\PrefixedFilesystem;
use Nanbando\Tests\TestCase;

class PrefixedFilesystemTest extends TestCase
{
    public function testGetPrefix()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $prefixedFilesystem = new PrefixedFilesystem($filesystem->reveal(), 'test');

        $this->assertEquals('test', $prefixedFilesystem->getPrefix());
    }

    public function testAddFile()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystem->addFile('test.txt', 'test/test.txt')->willReturn($filesystem->reveal())->shouldBeCalled();

        $prefixedFilesystem = new PrefixedFilesystem($filesystem->reveal(), 'test');
        $prefixedFilesystem->addFile('test.txt', 'test.txt');

    }

    public function testClose()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $prefixedFilesystem = new PrefixedFilesystem($filesystem->reveal(), 'test');
        $prefixedFilesystem->close();

        $filesystem->close()->shouldBeCalled();
    }
}
