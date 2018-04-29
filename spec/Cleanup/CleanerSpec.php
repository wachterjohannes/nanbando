<?php

namespace spec\Nanbando\Cleanup;

use Nanbando\Cleanup\Cleaner;
use Nanbando\Cleanup\StrategyInterface;
use Nanbando\Clock\ClockInterface;
use Nanbando\Console\OutputFormatter;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class CleanerSpec extends ObjectBehavior
{
    public function let(
        OutputFormatter $output,
        ClockInterface $clock,
        Filesystem $filesystem,
        StrategyInterface $strategy,
        \DateTimeImmutable $dateTime
    ) {
        $this->beConstructedWith($output, $clock, $filesystem, $strategy);

        $clock->getDateTime()->willReturn($dateTime);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Cleaner::class);
    }

    // TODO more specs
}
