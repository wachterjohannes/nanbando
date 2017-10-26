<?php

namespace Nanbando\Console;

use Nanbando\Client\ClientFactory;
use Nanbando\Client\ClientInterface;
use Nanbando\Host\HostInterface;
use Nanbando\Host\HostRegistry;
use Nanbando\Nanbando;
use Nanbando\Task\TaskRegistry;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends SymfonyApplication
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var TaskCommandFactory
     */
    private $taskCommandFactory;

    /**
     * @var TaskRegistry
     */
    private $taskRegistry;

    /**
     * @var HostRegistry
     */
    private $hostRegistry;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        TaskCommandFactory $taskCommandFactory,
        TaskRegistry $taskRegistry,
        HostRegistry $hostRegistry,
        ClientFactory $clientFactory
    ) {
        parent::__construct('Nanbando');

        $this->input = $input;
        $this->output = $output;
        $this->taskCommandFactory = $taskCommandFactory;
        $this->taskRegistry = $taskRegistry;
        $this->hostRegistry = $hostRegistry;
        $this->clientFactory = $clientFactory;
    }

    public function getConfig(): string
    {
        return Nanbando::get()
            ->getParameterBag()
            ->resolveString($this->getOption(['--config', '-c'], '%cwd%/nanbando.php'));
    }

    public function getHost(): HostInterface
    {
        $host = $this->getOption(['--host'], 'localhost');

        return $this->hostRegistry->get($host);
    }

    public function getClient(): ClientInterface
    {
        return $this->clientFactory->create($this->getHost());
    }

    public function initialize(): void
    {
        foreach ($this->taskRegistry->getTasks() as $name => $task) {
            $this->add($this->taskCommandFactory->create($name, $task));
        }
    }

    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        return parent::run($input ?: $this->input, $output ?: $this->output);
    }

    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(
            new InputOption(
                'config', 'c', InputOption::VALUE_REQUIRED, 'Path to configuration file', getcwd() . '/nanbando.php'
            )
        );
        $definition->addOption(
            new InputOption(
                'host', null, InputOption::VALUE_REQUIRED, 'TODO', 'localhost'
            )
        );

        return $definition;
    }

    protected function getOption(array $names, string $default)
    {
        foreach ($names as $name) {
            if ($this->input->hasParameterOption($name, true)) {
                return $this->input->getParameterOption($name, null, true);
            }
        }

        return $default;
    }
}
