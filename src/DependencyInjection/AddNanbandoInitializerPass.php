<?php

namespace Nanbando\DependencyInjection;

use Nanbando\Console\Command\InitCommand;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddNanbandoInitializerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $references = [];
        foreach ($container->findTaggedServiceIds('nanbando.initializer') as $id => $tags) {
            foreach ($tags as $attributes) {
                $references[$attributes['type']][$attributes['alias']] = new Reference($id);
            }
        }

        $definition = $container->findDefinition(InitCommand::class);
        $definition->replaceArgument(0, $references);
    }
}
