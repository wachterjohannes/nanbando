<?php

namespace Nanbando;

use Nanbando\Backup\BackupTaskCollection;
use Nanbando\Console\Application;
use Nanbando\Plugin\PluginInterface;
use Nanbando\Task\Task;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

function attach(string $name, PluginInterface $plugin, ?array $processes = null): void
{
    $nanbando = Nanbando::get();
    $application = $nanbando->getService(Application::class);

    $process = $application->getProcess();
    if ($processes && $process && !in_array($process, $processes)) {
        return;
    }

    /** @var BackupTaskCollection $backupTaskCollection */
    $backupTaskCollection = $nanbando->getService(BackupTaskCollection::class);

    $input = $nanbando->getService(InputInterface::class);
    $output = $nanbando->getService(OutputInterface::class);

    $backupTaskCollection->register($name, new Task([$plugin, 'backup'], [$input, $output]));
}

registerTask('backup', Nanbando::get()->getService(BackupTaskCollection::class))
    ->setDescription('Create a backup archive')
    ->addOption(new InputOption('message', 'm', InputOption::VALUE_REQUIRED, 'Message describe the backup in detail'))
    ->addArgument(new InputArgument('label', InputArgument::OPTIONAL, 'Used as part of the filename'));
