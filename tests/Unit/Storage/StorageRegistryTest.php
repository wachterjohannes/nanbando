<?php

namespace Nanbando\Tests\Unit\Storage;

use Nanbando\Storage\StorageInterface;
use Nanbando\Storage\StorageRegistry;
use PHPUnit\Framework\TestCase;

class StorageRegistryTest extends TestCase
{
    public function testRegister()
    {
        $registry = new StorageRegistry();
        $this->assertEquals(
            $registry,
            $registry->register('test', $this->prophesize(StorageInterface::class)->reveal())
        );

        $this->assertInstanceOf(StorageInterface::class, $registry->get('test'));
    }

    public function testGet()
    {
        $storage = $this->prophesize(StorageInterface::class)->reveal();

        $registry = new StorageRegistry();
        $this->assertEquals($registry, $registry->register('test', $storage));

        $this->assertEquals($storage, $registry->get('test'));
    }
}
