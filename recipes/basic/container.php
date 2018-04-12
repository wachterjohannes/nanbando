<?php

namespace Nanbando;

use Nanbando\DependencyInjection\SetServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

function containerBuilder(): ContainerBuilder
{
    global $container;

    return $container;
}

function registerService(string $id, $instance): Definition
{
    $containerBuilder = containerBuilder();
    $definition = $containerBuilder->register($id)->setSynthetic(true);

    foreach ($containerBuilder->getCompiler()->getPassConfig()->getPasses() as $pass) {
        if ($pass instanceof SetServicesPass) {
            $pass->addService($id, $instance);
        }
    }

    return $definition;
}

function service(string $id)
{
    return containerBuilder()->get($id);
}
