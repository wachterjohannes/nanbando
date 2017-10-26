<?php

namespace Nanbando\Host;

class HostRegistry
{
    /**
     * @var HostInterface[]
     */
    private $hosts = [];

    public function __construct()
    {
        $this->hosts['localhost'] = new Localhost();
    }

    public function register(HostInterface $host): self
    {
        $this->hosts[$host->getName()] = $host;

        return $this;
    }

    public function get(string $name): HostInterface
    {
        return $this->hosts[$name];
    }
}
