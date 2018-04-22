<?php

namespace Nanbando\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class BackupFileContext implements Context
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
     * @var string
     */
    private $dateTimeString;

    /**
     * @var string
     */
    private $fileContent;

    public function __construct(Filesystem $filesystem, FileContext $fileContext)
    {
        $this->filesystem = $filesystem;
        $this->fileContext = $fileContext;
    }

    /**
     * @Given /^I stop the time at "([^"]*)"$/
     */
    public function iStopTheTimeAt($dateTimeString)
    {
        $this->dateTimeString = $dateTimeString;

        if ($this->fileContent) {
            $this->thereExistsFollowingFile(new PyStringNode([$this->fileContent], 0));
        }
    }

    /**
     * @Given there exists following "backup.php" file
     */
    public function thereExistsFollowingFile(PyStringNode $string)
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

        $this->fileContent = $string;
        $content .= PHP_EOL . PHP_EOL . trim($this->fileContent->getRaw());

        $this->filesystem->dumpFile(Path::join($this->fileContext->getWorkingDirectory(), 'backup.php'), $content);
    }
}
