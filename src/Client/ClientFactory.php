<?php

namespace Nanbando\Client;

use Nanbando\Host\HostInterface;
use Nanbando\Process\ProcessFactory;

class ClientFactory
{
    /**
     * @var ClientInterface[]
     */
    private $clients = [];

    /**
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * @var int
     */
    private $timeout;

    public function __construct(ProcessFactory $processFactory, int $timeout = 300)
    {
        $this->processFactory = $processFactory;
        $this->timeout = $timeout;
    }

    public function create(HostInterface $host)
    {
        if (array_key_exists($host->getName(), $this->clients)) {
            return $this->clients[$host->getName()];
        }

        return $this->clients[$host->getName()] = new SshClient($this->processFactory, $host, $this->timeout);
    }
}
