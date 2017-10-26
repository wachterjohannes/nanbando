<?php

namespace Nanbando\Tests\Unit\Backup\Context;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Filesystem\PrefixedFilesystem;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class BackupContextTest extends TestCase
{
    public function testParameter()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal());
        $this->assertEquals($context, $context->set('parameter', 'test'));
        $this->assertEquals('test', $context->get('parameter'));
    }

    public function testNamedParameter()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal(), 'test');
        $this->assertEquals($context, $context->set('parameter', 'test'));
        $this->assertEquals('test', $context->get('parameter'));
    }

    public function testFilesystem()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal());
        $this->assertEquals($filesystem->reveal(), $context->getFilesystem());
    }

    public function testName()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal(), 'test');
        $this->assertEquals('test', $context->getName());
    }

    public function testOpen()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal());

        $context2 = $context->open('layer-2');
        $this->assertInstanceOf(BackupContext::class, $context2);
        $this->assertEquals('layer-2', $context2->getName());
        $this->assertInstanceOf(PrefixedFilesystem::class, $context2->getFilesystem());
        $this->assertEquals('layer-2', $context2->getFilesystem()->getPrefix());
    }

    public function testNamedOpen()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal(), 'layer-1');

        $context2 = $context->open('layer-2');
        $this->assertInstanceOf(BackupContext::class, $context2);
        $this->assertEquals('layer-1.layer-2', $context2->getName());
        $this->assertInstanceOf(PrefixedFilesystem::class, $context2->getFilesystem());
        $this->assertEquals('layer-1/layer-2', $context2->getFilesystem()->getPrefix());
    }

    public function testClose()
    {
        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal());

        $this->assertNull($context->close());
        $filesystem->close()->shouldBeCalled();
    }

    public function testCloseWithParent()
    {
        $parentContext = $this->prophesize(BackupContext::class);
        $parameterBag = $this->prophesize(ParameterBag::class);

        $parentContextReveal = $parentContext->reveal();

        $class = new \ReflectionClass($parentContextReveal);
        $property = $class->getProperty('parameterBag');
        $property->setAccessible(true);
        $property->setValue($parentContextReveal, $parameterBag->reveal());

        $filesystem = $this->prophesize(FilesystemInterface::class);

        $context = new BackupContext($filesystem->reveal(), 'layer-1', $parentContextReveal);

        $this->assertEquals($parentContextReveal, $context->close());
        $filesystem->close()->shouldNotBeCalled();

        $parameterBag->add(Argument::type('array'))->shouldBeCalled();
    }
}
