<?php

namespace Nanbando\Tests\Unit\Task;

use Nanbando\Task\Task;
use Nanbando\Tests\TestCase;

class TaskTest extends TestCase
{
    public function testConstructor()
    {
        $task = new Task(
            function () {
                return 1;
            }
        );

        $this->assertEquals(1, $task->invoke());
    }

    public function testConstructorWithParameter()
    {
        $task = new Task(
            function ($x) {
                return $x;
            },
            [1]
        );

        $this->assertEquals(1, $task->invoke());
    }

    public function testInvoke()
    {
        $task = new Task(
            function () {
                return 1;
            }
        );

        $this->assertEquals(1, $task->invoke());
    }

    public function testInvokeWithParameter()
    {
        $task = new Task(
            function ($x) {
                return $x;
            }
        );

        $this->assertEquals(1, $task->invoke([1]));
    }

    public function testSetParameter()
    {
        $task = new Task(
            function ($x) {
                return $x;
            }
        );

        $this->assertEquals($task, $task->setParameter([1]));
        $this->assertEquals(1, $task->invoke());
    }

    public function testBefore()
    {
        $task = new Task(
            function () {
                return 1;
            }
        );

        $x = false;
        $this->assertEquals(
            $task,
            $task->before(
                function () use (&$x) {
                    $x = true;
                }
            )
        );

        $this->assertEquals(1, $task->invoke());
        $this->assertTrue($x);
    }

    public function testAfter()
    {
        $task = new Task(
            function () {
                return 1;
            }
        );

        $x = false;
        $this->assertEquals(
            $task,
            $task->after(
                function () use (&$x) {
                    $x = true;
                }
            )
        );

        $this->assertEquals(1, $task->invoke());
        $this->assertTrue($x);
    }
}
