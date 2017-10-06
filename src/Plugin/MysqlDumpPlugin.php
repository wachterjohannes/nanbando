<?php

namespace Nanbando\Plugin;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class MysqlDumpPlugin implements PluginInterface
{
    public static function create(string $host, string $userName, string $database): self
    {
        return new MysqlDumpPlugin();
    }

    public static function autoConfigure(ParameterBag $parameter): self
    {
        return new MysqlDumpPlugin();
    }
}
