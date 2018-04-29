<?php

namespace Nanbando;

use Nanbando\Cleanup\StrategyInterface;

function cleanupStrategy(StrategyInterface $cleanupStrategy): void
{
    registerService('nanbando.cleanup.strategy', $cleanupStrategy);
}
