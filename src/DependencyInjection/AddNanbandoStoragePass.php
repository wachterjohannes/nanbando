<?php

namespace Nanbando\DependencyInjection;

use Nanbando\Storage\Storage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddNanbandoStoragePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $references = [];
        foreach ($container->findTaggedServiceIds('nanbando.storage') as $id => $tags) {
            foreach ($tags as $attributes) {
                $references[$attributes['storage']] = new Reference($id);
            }
        }

        $definition = $container->findDefinition(Storage::class);
        $definition->replaceArgument(1, $references);
    }
}
