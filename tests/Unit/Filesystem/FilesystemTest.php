<?php

namespace Nanbando\Tests\Unit\Filesystem;

use Nanbando\Filesystem\Filesystem;
use Nanbando\Filesystem\FilesystemAdapterInterface;
use Nanbando\Filesystem\FilesystemDecorator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class FilesystemTest extends TestCase
{
    public function testDecorate()
    {
        $adapter = $this->prophesize(FilesystemAdapterInterface::class);

        $filesystem = new Filesystem($adapter->reveal(), sys_get_temp_dir());

        $this->assertInstanceOf(FilesystemDecorator::class, $filesystem->decorate('prefix'));
    }

    public function testTempFilename()
    {
        $adapter = $this->prophesize(FilesystemAdapterInterface::class);

        $filesystem = new Filesystem($adapter->reveal(), sys_get_temp_dir());

        $this->assertFileIsWritable($filesystem->tempFilename());
    }

    public function testAddContent()
    {
        $adapter = $this->prophesize(FilesystemAdapterInterface::class);

        $filesystem = new Filesystem($adapter->reveal(), sys_get_temp_dir());
        $adapter->addFile(
            Argument::that(
                function (string $filename) {
                    if (!is_file($filename)) {
                        return true;
                    }

                    $content = file_get_contents($filename);

                    return $content === 'test';
                }
            ),
            'test.txt'
        )->shouldBeCalled()->willReturn($adapter->reveal());

        $this->assertEquals($filesystem, $filesystem->addContent('test', 'test.txt'));
    }

    public function testAddFile()
    {
        $adapter = $this->prophesize(FilesystemAdapterInterface::class);

        $filesystem = new Filesystem($adapter->reveal(), sys_get_temp_dir());
        $adapter->addFile('test.txt', 'test.txt')->shouldBeCalled()->willReturn($adapter->reveal());

        $this->assertEquals($filesystem, $filesystem->addFile('test.txt', 'test.txt'));
    }

    public function testClose()
    {
        $adapter = $this->prophesize(FilesystemAdapterInterface::class);

        $filesystem = new Filesystem($adapter->reveal(), sys_get_temp_dir());
        $adapter->close()->shouldBeCalled();

        $filesystem->close();
    }
}
