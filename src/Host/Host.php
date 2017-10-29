<?php

namespace Nanbando\Host;

class Host implements HostInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $hostName;

    /**
     * @var int
     */
    private $port = 22;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $user;

    public function __construct(string $name, string $hostName)
    {
        $this->name = $name;
        $this->hostName = $hostName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHostName(): string
    {
        return $this->hostName;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): HostInterface
    {
        $this->port = $port;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): HostInterface
    {
        $this->directory = $directory;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): HostInterface
    {
        $this->user = $user;

        return $this;
    }

    public function isLocalhost(): bool
    {
        return 'localhost' === $this->name;
    }
}
