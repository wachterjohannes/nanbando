<?php

namespace Nanbando\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use splitbrain\PHPArchive\Tar;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class FileContext implements Context
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $tmpDirectory;

    /**
     * @var string
     */
    private $backupDirectory;

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @var string
     */
    private $latestFile;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getTmpDirectory(): string
    {
        return $this->tmpDirectory;
    }

    public function getBackupDirectory(): string
    {
        return $this->backupDirectory;
    }

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    /**
     * @Given the resources directory is clean
     * @Given the resources directory contains following files
     */
    public function theResourcesDirectoryIsClean(?TableNode $table = null)
    {
        $this->workingDirectory = __DIR__ . '/../Resources';
        $this->tmpDirectory = Path::join($this->workingDirectory, 'var', 'tmp');
        $this->backupDirectory = Path::join($this->workingDirectory, 'var', 'backups');

        $this->filesystem->remove(
            [
                Path::join($this->workingDirectory, 'var'),
                Path::join($this->workingDirectory, 'uploads'),
            ]
        );

        if (!$table) {
            return;
        }

        foreach ($table as $row) {
            $this->filesystem->copy(
                Path::join(__DIR__, 'Resources', 'files', basename($row['name'])),
                Path::join($this->workingDirectory, $row['name'])
            );
        }
    }

    /**
     * @When I am in the resources directory
     */
    public function iAmInTheResourcesDirectory()
    {
        $this->workingDirectory = __DIR__ . '/../Resources';
        $this->tmpDirectory = Path::join($this->workingDirectory, 'var', 'tmp');
    }

    /**
     * @Given /^The backup-archive "([^"]*)" should exists$/
     */
    public function theBackupArchiveShouldExists(string $fileName)
    {
        $this->theFileShouldExists($fileName);

        $this->extract($fileName);
    }

    /**
     * @Then /^The backup-archive "([^"]*)" should include following files$/
     */
    public function theBackupArchiveShouldIncludeFollowingFiles(string $fileName, TableNode $table)
    {
        $this->extract($fileName);

        $this->shouldIncludeFollowingFiles($table);
    }

    /**
     * @Then /^should contain following files$/
     */
    public function shouldIncludeFollowingFiles(TableNode $table)
    {
        // TODO check also metadata in database

        foreach ($table as $row) {
            $filePath = Path::join($this->tmpDirectory, $row['name']);

            Assert::fileExists($filePath);
            Assert::eq(hash_file('sha224', $filePath), $row['hash']);
            Assert::eq(filesize($filePath), intval($row['size']));
        }
    }

    /**
     * @Given /^The backup\-archive "([^"]*)" should contain following parameters$/
     */
    public function theBackupArchiveShouldContainFollowingParameters(string $fileName, TableNode $table)
    {
        $this->extract($fileName);

        $this->theDatabaseShouldContainFollowingParameters($table);
    }

    /**
     * @Given /^should contain following parameters$/
     */
    public function theDatabaseShouldContainFollowingParameters(TableNode $table)
    {
        $database = $this->openMetadata();

        foreach ($table as $row) {
            $actual = $database[$row['name']];
            $expected = $row['value'];

            if ('datetime' === $row['type']) {
                $expected = (new \DateTime($expected))->format(\DateTime::RFC3339_EXTENDED);
                $actual = (new \DateTime($actual['date']))->format(\DateTime::RFC3339_EXTENDED);
            }

            Assert::eq($expected, $actual);
        }
    }

    /**
     * @Given /^The file "([^"]*)" should exists$/
     */
    public function theFileShouldExists(string $fileName)
    {
        $filePath = Path::join($this->workingDirectory, $fileName);
        Assert::fileExists($filePath);

        $this->latestFile = $filePath;
    }

    /**
     * @Given /^I cleanup the backup directory$/
     * @Given /^I cleanup the directory "([^"]*)"$/
     */
    public function iCleanupTheDirectory(string $directory = 'var')
    {
        $this->filesystem->remove(Path::join($this->workingDirectory, $directory));
    }

    /**
     * @Given /^I cleanup the resources directory$/
     */
    public function iCleanupTheResourcesDirectory()
    {
        $this->filesystem->remove(
            [
                Path::join($this->workingDirectory, 'var'),
                Path::join($this->workingDirectory, 'uploads'),
            ]
        );
    }

    /**
     * @Given /^should have following attributes$/
     */
    public function shouldHaveFollowingAttributes(TableNode $table)
    {
        foreach ($table as $row) {
            Assert::eq(hash_file('sha224', $this->latestFile), $row['hash']);
            Assert::eq(filesize($this->latestFile), $row['size']);
        }
    }

    /**
     * @Given /^The backup\-archive "([^"]*)" should contain following file\-metadata$/
     */
    public function theBackupArchiveShouldContainFollowingFileMetadata(string $fileName, TableNode $table)
    {
        $this->extract($fileName);

        $this->shouldIncludeFollowingFiles($table);
    }

    /**
     * @Given /^should contain following file\-metadata$/
     */
    public function shouldContainFollowingFileMetadata(TableNode $table)
    {
        $metadata = $this->openMetadata()['metadata'];

        // subtract header row
        Assert::count($metadata, count($table->getRows()) - 1);

        foreach ($table as $row) {
            $actual = $metadata[$row['name']];

            Assert::eq($row['size'], $actual['size']);
            Assert::eq($row['hash'], $actual['hash']);
        }
    }

    /**
     * @Given /^The backup\-archive "([^"]*)" should not contain following file$/
     */
    public function theBackupArchiveShouldNotContainFollowingFile(string $fileName, TableNode $table)
    {
        $this->extract($fileName);

        $this->shouldNotContainFollowingFile($table);
    }

    /**
     * @Given /^should not contain following file$/
     */
    public function shouldNotContainFollowingFile(TableNode $table)
    {
        foreach ($table as $row) {
            Assert::fileNotExists(Path::join($this->tmpDirectory, $row['name']));
        }
    }

    private function extract(string $fileName): void
    {
        $this->filesystem->remove($this->tmpDirectory);

        $tar = new Tar();
        $tar->open(Path::join($this->workingDirectory, $fileName));
        $tar->extract($this->tmpDirectory);
        $tar->close();
    }

    private function openMetadata(): array
    {
        return json_decode(file_get_contents(Path::join($this->tmpDirectory, '/database.json')), true);
    }
}
