<?php

namespace Nanbando\Tests;

use Nanbando\Nanbando;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Nanbando::reset();
    }
}
