<?php

namespace spec\Nanbando\Clock;

use Nanbando\Clock\Clock;
use Nanbando\Clock\ClockInterface;
use PhpSpec\ObjectBehavior;

class ClockSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Clock::class);
    }

    public function it_should_implement_clock()
    {
        $this->shouldBeAnInstanceOf(ClockInterface::class);
    }

    public function it_should_return_date_time()
    {
        $this->getDateTime()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }
}
