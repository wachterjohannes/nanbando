<?php

namespace Nanbando;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

function import(string $file): void
{
    Nanbando::get()->import($file);
}

function parameters(): ParameterBag
{
    return Nanbando::get()->getParameterBag();
}

function set(string $name, $value): void
{
    $parameterBag = parameters();
    $parameterBag->set($name, $value);
}

function get(string $name, $default = null)
{
    $parameterBag = parameters();
    if (!$parameterBag->has($name)) {
        return $default;
    }

    return $parameterBag->get($name);
}
