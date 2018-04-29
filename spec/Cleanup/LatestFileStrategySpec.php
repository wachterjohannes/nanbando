<?php

namespace spec\Nanbando\Cleanup;

use Nanbando\Cleanup\LatestFileStrategy;
use PhpSpec\ObjectBehavior;

class LatestFileStrategySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(LatestFileStrategy::class);
    }

    // TODO more specs
}
