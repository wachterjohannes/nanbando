<?php

namespace Nanbando\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SetServicesPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $services = [];

    public function addService(string $id, $instance): void
    {
        $this->services[$id] = $instance;
    }

    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $id => $instance) {
            $container->set($id, $instance);
        }
    }
}
