<?php

namespace Nanbando\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Webmozart\PathUtil\Path;

class DataFixturesContext implements Context
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FileContext
     */
    private $fileContext;

    /**
     * @var BackupFileContext
     */
    private $backupFileContext;

    /**
     * @var ConsoleContext
     */
    private $consoleContext;

    public function __construct(
        Filesystem $filesystem,
        FileContext $fileContext,
        BackupFileContext $backupFileContext,
        ConsoleContext $consoleContext
    ) {
        $this->filesystem = $filesystem;
        $this->fileContext = $fileContext;
        $this->backupFileContext = $backupFileContext;
        $this->consoleContext = $consoleContext;
    }

    /**
     * @Given /^the backup-archive "([^"]*)" exists$/
     * @Given /^the backup\-archive "([^"]*)" exists in the folder "([^"]*)"$/
     */
    public function theBackupArchiveExists(string $archiveName, ?string $directory = null)
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Ymd-His', $archiveName);
        $this->backupFileContext->iStopTheTimeAt($dateTime->format('Y-m-d H:i'));

        $this->consoleContext->iRun('bin/nanbando backup');

        if ($directory) {
            $this->filesystem->mkdir(Path::join($this->fileContext->getWorkingDirectory(), $directory));
            $this->filesystem->rename(
                Path::join($this->fileContext->getBackupDirectory(), $archiveName . '.tar.gz'),
                Path::join($this->fileContext->getWorkingDirectory(), $directory, $archiveName . '.tar.gz')
            );
            $this->filesystem->rename(
                Path::join($this->fileContext->getBackupDirectory(), $archiveName . '.json'),
                Path::join($this->fileContext->getWorkingDirectory(), $directory, $archiveName . '.json')
            );
        }
    }

    /**
     * @Given /^the differential backup\-archive "([^"]*)" ontop of "([^"]*)" exists$/
     */
    public function theDifferentialBackupArchiveOntopOfExists(string $archiveName, string $parentArchiveName)
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Ymd-His', $archiveName);
        $this->backupFileContext->iStopTheTimeAt($dateTime->format('Y-m-d H:i'));

        $this->consoleContext->iRun('bin/nanbando backup:differential ' . $parentArchiveName);
    }

    /**
     * @Given /^the backup\-archive "([^"]*)" exists with following files$/
     * @Given /^the backup\-archive "([^"]*)" exists with following files in the folder "([^"]*)"$/
     */
    public function theBackupArchiveExistsWithFollowingFiles(string $archiveName, TableNode $table, ?string $directory = null)
    {
        $this->theBackupArchiveExists($archiveName);

        foreach ($table as $row) {
            $this->filesystem->copy(
                Path::join(__DIR__, 'Resources', 'files', basename($row['name'])),
                Path::join($this->fileContext->getWorkingDirectory(), $row['name'])
            );
        }

        $dateTime = \DateTimeImmutable::createFromFormat('Ymd-His', $archiveName);
        $this->backupFileContext->iStopTheTimeAt($dateTime->format('Y-m-d H:i'));

        $this->consoleContext->iRun('bin/nanbando backup');

        foreach ($table as $row) {
            $this->filesystem->remove(Path::join($this->fileContext->getWorkingDirectory(), $row['name']));
        }

        if ($directory) {
            $this->filesystem->mkdir(Path::join($this->fileContext->getWorkingDirectory(), $directory));
            $this->filesystem->rename(
                Path::join($this->fileContext->getBackupDirectory(), $archiveName . '.tar.gz'),
                Path::join($this->fileContext->getWorkingDirectory(), $directory, $archiveName . '.tar.gz')
            );
            $this->filesystem->rename(
                Path::join($this->fileContext->getBackupDirectory(), $archiveName . '.json'),
                Path::join($this->fileContext->getWorkingDirectory(), $directory, $archiveName . '.json')
            );
        }
    }

    /**
     * @Given /^I extract "([^"]*)" to "([^"]*)"$/
     */
    public function iExtractTo(string $archive, string $folder)
    {
        $this->filesystem->mkdir(Path::join($this->fileContext->getWorkingDirectory(), $folder));

        $process = new Process(sprintf('unzip %s -d %s', $archive, $folder), $this->fileContext->getWorkingDirectory());
        $process->run();
    }

    /**
     * @Given /^I dump following content to the file "([^"]*)"$/
     */
    public function iDumpFollowingContentToTheFile(string $fileName, PyStringNode $string)
    {
        $this->filesystem->dumpFile(Path::join($this->fileContext->getWorkingDirectory(), $fileName), $string->getRaw());
    }

    /**
     * @When /^I remove the file "([^"]*)"$/
     */
    public function iRemoveTheFile(string $fileName)
    {
        $this->filesystem->remove(Path::join($this->fileContext->getWorkingDirectory(), $fileName));
    }
}
