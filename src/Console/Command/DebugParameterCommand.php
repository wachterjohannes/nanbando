<?php

namespace Nanbando\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

class DebugParameterCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $backupRunner)
    {
        parent::__construct();

        $this->container = $backupRunner;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['Name', 'Value']);

        foreach ($this->container->getParameterBag()->all() as $key => $value) {
            $table->addRow([$key, json_encode($value)]);
        }

        $table->render();
    }
}
