<?php

namespace Nanbando\Client;

interface ClientInterface
{
    public function run(string $command, array $config = []);
}
