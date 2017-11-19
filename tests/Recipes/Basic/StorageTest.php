<?php

namespace Nanbando\Tests\Recipes\Basic;

use Nanbando\Nanbando;
use Nanbando\Storage\StorageInterface;
use Nanbando\Storage\StorageRegistry;
use Nanbando\Tests\TestCase;
use function Nanbando\storage;

class StorageTest extends TestCase
{
    public function testStorage()
    {
        $storage = $this->prophesize(StorageInterface::class);

        $storage = storage('test', $storage->reveal());
        $this->assertInstanceOf(StorageInterface::class, $storage);

        /** @var StorageRegistry $storageRegistry */
        $storageRegistry = Nanbando::get()->getService(StorageRegistry::class);

        $this->assertEquals($storage, $storageRegistry->get('test'));
    }
}
