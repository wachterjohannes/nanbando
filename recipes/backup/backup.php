<?php

namespace Nanbando;

use Nanbando\Script\ScriptInterface;

function attach(string $name, ScriptInterface $script): void
{
    registerService('nanbando.script.' . $name, $script)
        ->addTag('nanbando.script', ['script' => $name]);
}
