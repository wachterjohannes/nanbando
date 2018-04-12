<?php

namespace Nanbando\Script;

class ScriptRegistry
{
    /**
     * @var ScriptInterface[]
     */
    private $scripts = [];

    public function __construct(array $scripts = [])
    {
        $this->scripts = $scripts;
    }

    /**
     * @return ScriptInterface[]
     */
    public function get(): array
    {
        return $this->scripts;
    }
}
