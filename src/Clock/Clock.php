<?php

namespace Nanbando\Clock;

class Clock implements ClockInterface
{
    public function getDateTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
