<?php

namespace Nanbando;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

function import(string $file): void
{
    $locator = new FileLocator(dirname($file));

    $loader = new DelegatingLoader(
        new LoaderResolver(
            [
                new YamlFileLoader(containerBuilder(), $locator),
                new PhpFileLoader(containerBuilder(), $locator),
                new XmlFileLoader(containerBuilder(), $locator),
            ]
        )
    );

    $loader->load(basename($file));
}

function parameters(): ParameterBagInterface
{
    return containerBuilder()->getParameterBag();
}

function set(string $name, $value): void
{
    parameters()->set($name, $value);
}

function get(string $name)
{
    return parameters()->resolveValue($name);
}
