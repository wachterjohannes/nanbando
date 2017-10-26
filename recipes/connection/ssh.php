<?php

namespace Nanbando;

use Nanbando\Client\ClientFactory;
use Nanbando\Console\Application;
use Nanbando\Host\HostInterface;

function run(string $command, array $config = [], HostInterface $host = null): string
{
    $nanbando = Nanbando::get();
    if (!$host) {
        $host = $nanbando->getService(Application::class)->getHost();
    }

    /** @var ClientFactory $clientFactory */
    $clientFactory = $nanbando->getService(ClientFactory::class);

    $client = $clientFactory->create($host);

    return $client->run($command, $config);
}
