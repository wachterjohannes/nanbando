<?php

namespace Nanbando\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface PluginInterface
{
    public function backup(BackupContext $context, InputInterface $input, OutputInterface $output);
}
