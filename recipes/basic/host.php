<?php

namespace Nanbando;

use Nanbando\Host\Host;
use Nanbando\Host\HostInterface;
use Nanbando\Host\HostRegistry;

function host(string $name, string $hostName): HostInterface
{
    $host = new Host($name, $hostName);

    /** @var HostRegistry $hostRegistry */
    $hostRegistry = Nanbando::get()->getService(HostRegistry::class);
    $hostRegistry->register($host);

    return $host;
}
