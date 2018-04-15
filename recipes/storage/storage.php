<?php

namespace Nanbando;

use Nanbando\Storage\StorageInterface;

function storage(string $name, StorageInterface $storage): void
{
    registerService('nanbando.storage.' . $name, $storage)
        ->addTag('nanbando.storage', ['storage' => $name]);
}
