<?php

namespace Nanbando\Initializer;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webmozart\Assert\Assert;

class DirectoryStorageInitializer implements InitializerInterface
{
    /**
     * @var string
     */
    private $currentDirectory;

    public function __construct(string $currentDirectory)
    {
        $this->currentDirectory = $currentDirectory;
    }

    public function interact(InputInterface $input, OutputInterface $output): array
    {
        $helper = new QuestionHelper();

        $question = new Question('Name: ');
        $question->setValidator(
            function ($value) {
                Assert::notNull($value);
                Assert::minLength(3, $value);

                return $value;
            }
        );
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Directory (relative from here): ./');
        $question->setValidator(
            function ($value) {
                Assert::notNull($value);

                return $value;
            }
        );
        $directory = $helper->ask($input, $output, $question);

        return [
            'name' => $name,
            'directory' => $directory,
        ];
    }

    public function getTemplate(array $options): string
    {
        $name = $options['name'];
        $directory = $options['directory'];

        return <<<EOF
storage('$name', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/$directory')));
EOF;
    }
}
