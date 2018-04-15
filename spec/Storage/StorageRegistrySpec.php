<?php

namespace spec\Nanbando\Storage;

use Nanbando\Storage\RemoteStorage;
use Nanbando\Storage\StorageRegistry;
use PhpSpec\ObjectBehavior;

class StorageRegistrySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(StorageRegistry::class);
    }

    public function it_should_return_storage(
        RemoteStorage $storage
    ) {
        $this->beConstructedWith(['test' => $storage]);

        $this->get('test')->shouldEqual($storage);
    }
}
