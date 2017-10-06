<?php

namespace Nanbando\Plugin;

class DirectoryPlugin implements PluginInterface
{
    public static function create(string $directory): self
    {
        return new DirectoryPlugin();
    }
}
