<?php

namespace Nanbando\DependencyInjection;

use Nanbando\Script\ScriptRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddNanbandoScriptPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $references = [];
        foreach ($container->findTaggedServiceIds('nanbando.script') as $id => $tags) {
            foreach ($tags as $attributes) {
                $references[$attributes['script']] = new Reference($id);
            }
        }

        $definition = $container->findDefinition(ScriptRegistry::class);
        $definition->replaceArgument(0, $references);
    }
}
