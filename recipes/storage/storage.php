<?php

namespace Nanbando;

use Nanbando\Storage\LocalStorage;
use Nanbando\Storage\RemoteStorage;
use Nanbando\Storage\StorageAdapterInterface;
use Symfony\Component\DependencyInjection\Reference;

function storage(string $name, StorageAdapterInterface $storage): void
{
    registerService('nanbando.storage.' . $name . '.adapter', $storage);

    registerService('nanbando.storage.' . $name)
        ->setClass(RemoteStorage::class)
        ->addArgument(new Reference(LocalStorage::class))
        ->addArgument(new Reference('nanbando.storage.' . $name . '.adapter'))
        ->addTag('nanbando.storage', ['storage' => $name]);
}
