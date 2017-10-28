<?php

namespace Nanbando\Tests\Recipes\Connection;

use Nanbando\Tests\TestCase;
use function Nanbando\host;
use function Nanbando\run;

class SshTest extends TestCase
{
    public function testRun()
    {
        if (getenv('TRAVIS')) {
            return $this->markTestSkipped('Travis cannot start local SSH-Server.');
        }

        $host = host('localhost', '127.0.0.1');

        $this->assertEquals('hello', trim(run('echo "hello"', ['tty' => false], $host)));
    }
}
