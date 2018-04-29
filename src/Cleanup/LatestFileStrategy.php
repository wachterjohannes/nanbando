<?php

namespace Nanbando\Cleanup;

class LatestFileStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $maximum;

    public function __construct(int $maximum = 3)
    {
        $this->maximum = $maximum;
    }

    public function determineArchives(array $archives): array
    {
        ksort($archives);
        $archives = array_values(array_reverse($archives));
        $archives = array_splice($archives, 0, $this->maximum);

        if (0 === count($archives)) {
            return [];
        }

        return call_user_func('array_merge', ...$archives);
    }
}
