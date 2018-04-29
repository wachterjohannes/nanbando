<?php

namespace Nanbando\Cleanup;

use Nanbando\Storage\ArchiveInfo;

interface StrategyInterface
{
    /**
     * @param ArchiveInfo[] $archives
     *
     * @return ArchiveInfo[]
     */
    public function determineArchives(array $archives): array;
}
