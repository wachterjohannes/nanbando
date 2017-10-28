<?php

namespace Nanbando\Tests\Unit\Filesystem;

use Nanbando\Filesystem\FilesystemDecorator;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Tests\TestCase;

class FilesystemDecoratorTest extends TestCase
{
    public function testGetName()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystem->getName()->willReturn('20170101-175310')->shouldBeCalled();

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');

        $this->assertEquals('20170101-175310', $decorator->getName());
    }

    public function testGetPrefix()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');

        $this->assertEquals('test', $decorator->getPrefix());
    }

    public function testTempFilename()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystem->tempFilename()->willReturn('test.txt')->shouldBeCalled();

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');
        $this->assertEquals('test.txt', $decorator->tempFilename());
    }

    public function testDecorate()
    {
        $decoratorDecorator = $this->prophesize(FilesystemInterface::class);

        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystem->decorate('test2')->willReturn($decoratorDecorator->reveal())->shouldBeCalled();

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');
        $this->assertEquals($decoratorDecorator->reveal(), $decorator->decorate('test2'));
    }

    public function testAddFile()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystem->addFile('test.txt', 'test/test.txt')->willReturn($filesystem->reveal())->shouldBeCalled();

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');
        $this->assertEquals($decorator, $decorator->addFile('test.txt', 'test.txt'));
    }

    public function testAddContent()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $filesystem->addContent('test', 'test/test.txt')->willReturn($filesystem->reveal())->shouldBeCalled();

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');
        $this->assertEquals($decorator, $decorator->addContent('test', 'test.txt'));
    }

    public function testClose()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $decorator = new FilesystemDecorator($filesystem->reveal(), 'test');
        $decorator->close();

        $filesystem->close()->shouldBeCalled();
    }
}
