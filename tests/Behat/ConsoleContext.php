<?php

namespace Nanbando\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;
use Webmozart\PathUtil\Path;

class ConsoleContext implements Context
{
    /**
     * @var FileContext
     */
    private $fileContext;

    /**
     * @var Process
     */
    private $process;

    public function __construct(FileContext $fileContext)
    {
        $this->fileContext = $fileContext;
    }

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun(string $argument): void
    {
        $path = Path::join(dirname(dirname(__DIR__)), $argument);
        $this->process = new Process($path, $this->fileContext->getWorkingDirectory());
        $this->process->run();
    }

    /**
     * @Then /^I should see "(.*)"$/
     */
    public function iShouldSee(string $arguments): void
    {
        foreach (explode('", "', $arguments) as $item) {
            Assert::contains($this->process->getOutput(), $item);
        }
    }

    /**
     * @Then /^I should not see "(.*)"$/
     */
    public function iShouldNotSee(string $arguments): void
    {
        foreach (explode('", "', $arguments) as $item) {
            Assert::notContains($this->process->getOutput(), $item);
        }
    }

    /**
     * @Given /^I should see following parameters$/
     */
    public function iShouldSeeFollowingParameters(TableNode $table)
    {
        $output = $this->process->getOutput();
        $lines = explode(PHP_EOL, $output);

        foreach ($table as $row) {
            $seen = false;
            foreach ($lines as $line) {
                if (false !== strpos($line, $row['name']) && false !== strpos($line, $row['value'])) {
                    $seen = true;
                }
            }

            if (!$seen) {
                throw new \RuntimeException(
                    sprintf(
                        'Cannot see parameter "%s" with value %s',
                        $row['name'],
                        $row['value']
                    )
                );
            }
        }
    }

    /**
     * @Then /^I should see an error containing "([^"]*)"$/
     */
    public function iShouldSeeAnErrorWith(string $arguments)
    {
        foreach (explode('", "', $arguments) as $item) {
            Assert::contains($this->process->getErrorOutput(), $item);
        }
    }
}
