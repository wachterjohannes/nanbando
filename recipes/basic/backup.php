<?php

namespace Nanbando;

use Nanbando\Backup\BackupTaskCollection;
use Nanbando\Plugin\PluginInterface;
use Nanbando\Task\Task;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

function attach(string $name, PluginInterface $plugin): void
{
    $nanbando = Nanbando::get();

    /** @var BackupTaskCollection $backupTaskCollection */
    $backupTaskCollection = $nanbando->getService(BackupTaskCollection::class);

    $input = $nanbando->getService(InputInterface::class);
    $output = $nanbando->getService(OutputInterface::class);

    $backupTaskCollection->register($name, new Task([$plugin, 'backup'], [$input, $output]));
}

registerTask('backup', Nanbando::get()->getService(BackupTaskCollection::class));
