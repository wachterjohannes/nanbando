<?php

namespace Nanbando\Host;

interface HostInterface
{
    public function getName(): string;

    public function getHostName(): string;

    public function setPort(int $port): self;
    public function getPort(): int;

    public function getDirectory(): ?string;
    public function setDirectory(string $directory): self;

    public function getUser(): ?string;
    public function setUser(string $user): self;
}
