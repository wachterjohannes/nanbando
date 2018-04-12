<?php

namespace Nanbando\Tests\Behat\Mocks;

use Nanbando\Clock\Clock;

class ClockMock extends Clock
{
    /**
     * @var \DateTimeImmutable
     */
    private $dateTime;

    public function __construct(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }
}
