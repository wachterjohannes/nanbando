<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Initializer\InitializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class InitCommand extends Command
{
    /**
     * @var InitializerInterface[][]
     */
    private $initializer;

    /**
     * @var string
     */
    private $currentDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var OutputFormatter
     */
    private $outputFormatter;

    public function __construct(
        array $initializer,
        string $currentDirectory,
        Filesystem $filesystem,
        OutputFormatter $outputFormatter
    ) {
        parent::__construct();

        $this->initializer = $initializer;
        $this->currentDirectory = $currentDirectory;
        $this->filesystem = $filesystem;
        $this->outputFormatter = $outputFormatter;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->outputFormatter->headline('Welcome to the nanbando "backup.php" generator');

        $path = Path::join($this->currentDirectory, 'backup.php');
        if ($this->filesystem->exists($path)) {
            throw new IOException(
                sprintf(
                    'The file "%s" already exist.',
                    $path
                )
            );
        }

        $content = <<<EOF
<?php

namespace Nanbando;

require_once __DIR__ . '/vendor/nanbando/nanbando/recipes/common.php';


EOF;

        $section = $this->outputFormatter->section();
        $section->info('Define your backup scripts');

        $content .= $this->determineScripts($input, $output);

        $output->writeln('');
        $content .= PHP_EOL . PHP_EOL;

        $content .= $this->determineStorages($input, $output);

        $output->writeln('');
        $output->writeln($content);

        $this->filesystem->dumpFile($path, $content);
    }

    private function determineScripts(InputInterface $input, OutputInterface $output): string
    {
        if (0 === count($this->initializer['script'])) {
            return '';
        }

        $helper = new QuestionHelper();
        $question = new ConfirmationQuestion('Would you like to define your scripts interactively [<info>yes</info>]');
        if (!$helper->ask($input, $output, $question)) {
            return '';
        }

        $output->writeln('');
        $content = '';

        $question = $this->createChoiceQuestion('script', array_keys($this->initializer['script']));
        while ($script = $helper->ask($input, $output, $question)) {
            $output->writeln(sprintf(PHP_EOL . 'Options for "%s" script:', $script));
            $options = $this->initializer['script'][$script]->interact($input, $output);
            $content .= $this->initializer['script'][$script]->getTemplate($options);

            $output->writeln('');
        }

        return $content;
    }

    private function determineStorages(InputInterface $input, OutputInterface $output): string
    {
        if (0 === count($this->initializer['storage'])) {
            return '';
        }

        $helper = new QuestionHelper();
        $question = new ConfirmationQuestion('Would you like to define your storages interactively [<info>yes</info>]');
        if (!$helper->ask($input, $output, $question)) {
            return '';
        }

        $output->writeln('');
        $content = '';

        $question = $this->createChoiceQuestion('storage', array_keys($this->initializer['storage']));
        while ($storage = $helper->ask($input, $output, $question)) {
            $output->writeln(sprintf(PHP_EOL . 'Options for "%s" storage:', $storage));
            $options = $this->initializer['storage'][$storage]->interact($input, $output);
            $content .= $this->initializer['storage'][$storage]->getTemplate($options);

            $output->writeln('');
        }

        return $content;
    }

    private function createChoiceQuestion(string $name, array $choices): ChoiceQuestion
    {
        $question = new ChoiceQuestion('Select a ' . $name . ' (nothing to abort):', $choices);
        $question->setValidator(
            function ($value) use ($choices) {
                if (in_array($value, $choices)) {
                    return $value;
                }

                if (null === $value) {
                    return $value;
                }

                if (!array_key_exists($value, $choices)) {
                    throw new \RuntimeException('Choice not available');
                }

                return $choices[$value];
            }
        );

        return $question;
    }
}
