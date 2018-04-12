<?php

namespace Nanbando\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use splitbrain\PHPArchive\Tar;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;
use Webmozart\PathUtil\Path;

class ConsoleContext implements Context
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @var string
     */
    private $dateTimeString;

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun(string $argument): void
    {
        $this->process = new Process(__DIR__ . '/../../' . $argument, $this->workingDirectory);
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

    /**
     * @When I am in the resources directory
     */
    public function iAmInTheResourcesDirectory()
    {
        $this->workingDirectory = __DIR__ . '/../Resources';
    }

    /**
     * @Given /^I set stop the time at "([^"]*)"$/
     */
    public function iSetStopTheTimeAt($dateTimeString)
    {
        $this->dateTimeString = $dateTimeString;
    }

    /**
     * @When There exists following :fileName file
     */
    public function thereExistsFollowingFile($fileName, PyStringNode $string)
    {
        $content = <<<'EOT'
<?php

namespace Nanbando;

require_once __DIR__ . '/../../recipes/common.php';

EOT;

        if ($this->dateTimeString) {
            $content .= PHP_EOL . 'containerBuilder()->getDefinition(\Nanbando\Clock\Clock::class)->setSynthetic(true);';
            $content .= PHP_EOL . 'containerBuilder()->set(\Nanbando\Clock\Clock::class, new \Nanbando\Tests\Behat\Mocks\ClockMock(new \DateTimeImmutable("' . $this->dateTimeString . '")));';
        }

        $content .= PHP_EOL . PHP_EOL . trim($string);

        file_put_contents($this->workingDirectory . '/' . $fileName, $content);
    }

    /**
     * @Then /^The backup-archive "([^"]*)" should include following files$/
     */
    public function theBackupArchiveShouldIncludeFollowingFiles(string $fileName, TableNode $table)
    {
        (new Filesystem())->remove($this->workingDirectory . '/var/tmp');

        $tmpDirectory = $this->workingDirectory . '/var/tmp';

        $tar = new Tar();
        $tar->open($this->workingDirectory . '/' . $fileName);
        $tar->extract($tmpDirectory);
        $tar->close();

        // TODO check also metadata in database

        foreach ($table as $row) {
            $filePath = $tmpDirectory . '/' . $row['name'];
            if (!file_exists($filePath)) {
                throw new \RuntimeException(sprintf('File "%s" does not exists', $row['name']));
            } elseif (hash_file('sha224', $filePath) !== $row['hash']) {
                throw new \RuntimeException(
                    sprintf(
                        'File "%s" does not match given hash. Expected: "%s" Actual: "%s"',
                        $row['name'],
                        $row['hash'],
                        hash_file('sha224', $filePath)
                    )
                );
            } elseif (filesize($filePath) !== intval($row['size'])) {
                throw new \RuntimeException(
                    sprintf(
                        'File "%s" does not match given size. Expected: "%s" Actual: "%s"',
                        $row['name'],
                        $row['size'],
                        filesize($filePath)
                    )
                );
            }
        }
    }

    /**
     * @Given /^The backup\-archive "([^"]*)" should contain following parameters$/
     */
    public function theBackupArchiveShouldContainFollowingParameters($fileName, TableNode $table)
    {
        (new Filesystem())->remove($this->workingDirectory . '/var/tmp');

        $tmpDirectory = $this->workingDirectory . '/var/tmp';

        $tar = new Tar();
        $tar->open($this->workingDirectory . '/' . $fileName);
        $tar->extract($tmpDirectory);
        $tar->close();

        $this->theDatabaseShouldContainFollowingParameters($table);
    }

    /**
     * @Given /^The file "([^"]*)" should exists$/
     */
    public function theFileShouldExists(string $fileName)
    {
        $filePath = $this->workingDirectory . '/' . $fileName;
        if (!file_exists($filePath)) {
            $existing = array_map(
                function (string $item) use ($filePath) {
                    return Path::makeRelative($item, dirname($filePath));
                },
                glob(dirname($filePath) . '/*')
            );

            throw new \RuntimeException(
                sprintf(
                    'File "%s" does not exists. Existing: "%s"' . PHP_EOL . '%s',
                    $fileName,
                    implode('", "', $existing),
                    $this->process->getOutput()
                )
            );
        }
    }

    /**
     * @Given /^I cleanup the backup directory$/
     */
    public function iCleanupTheBackupDirectory()
    {
        (new Filesystem())->remove($this->workingDirectory . '/var');
    }

    /**
     * @Given /^The database should contain following parameters$/
     */
    public function theDatabaseShouldContainFollowingParameters(TableNode $table)
    {
        $tmpDirectory = $this->workingDirectory . '/var/tmp';
        $database = json_decode(file_get_contents($tmpDirectory . '/database.json'), true);

        foreach ($table as $row) {
            $actual = $database[$row['name']];
            $expected = $row['value'];

            if ('datetime' === $row['type']) {
                $expected = (new \DateTime($expected))->format(\DateTime::RFC3339_EXTENDED);
                $actual = (new \DateTime($actual['date']))->format(\DateTime::RFC3339_EXTENDED);
            }

            if (!$expected === $actual) {
                throw new \RuntimeException(
                    sprintf(
                        'Parameter "%s" value does not match. Expected: "%s" Actual: "%s"',
                        $row['name'],
                        $expected,
                        $actual
                    )
                );
            }
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
}
