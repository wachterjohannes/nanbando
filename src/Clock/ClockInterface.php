<?php

namespace Nanbando\Clock;

interface ClockInterface
{
    public function getDateTime(): \DateTimeImmutable;
}
