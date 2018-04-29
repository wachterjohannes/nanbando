<?php

namespace Nanbando\Console\Command;

use Nanbando\Cleanup\Cleaner;
use Nanbando\Storage\LocalStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends Command
{
    /**
     * @var Cleaner
     */
    private $cleaner;

    /**
     * @var LocalStorage
     */
    private $localStorage;

    public function __construct(Cleaner $cleaner, LocalStorage $localStorage)
    {
        parent::__construct();

        $this->cleaner = $cleaner;
        $this->localStorage = $localStorage;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cleaner->clean($this->localStorage);
    }

    public function isEnabled()
    {
        return $this->cleaner->isEnabled();
    }
}
