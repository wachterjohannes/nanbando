<?php

namespace Nanbando\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

class ConsoleContext implements Context
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun(string $argument): void
    {
        $this->process = new Process($argument);
        $this->process->run();

        $this->process->wait();
    }

    /**
     * @Then /^I should see "(.*)"$/
     */
    public function iShouldSeeAndAnd(string $arguments): void
    {
        foreach (explode('", "', $arguments) as $item) {
            Assert::contains($this->process->getOutput(), $item);
        }
    }
}
