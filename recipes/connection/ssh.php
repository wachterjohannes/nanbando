<?php

namespace Nanbando;

use Nanbando\Client\ClientFactory;
use Nanbando\Host\HostInterface;

function run(string $command, array $config = [], HostInterface $host = null): string
{
    if (!$host) {
        // TODO host from context

        return '';
    }

    /** @var ClientFactory $clientFactory */
    $clientFactory = Nanbando::get()->getService(ClientFactory::class);

    $client = $clientFactory->create($host);

    return $client->run($command, $config);
}
