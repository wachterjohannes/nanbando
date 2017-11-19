<?php

namespace Nanbando;

use Nanbando\Storage\StorageCommunicator;
use Nanbando\Storage\StorageInterface;
use Nanbando\Storage\StorageRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

function storage(string $name, StorageInterface $storage): StorageInterface
{
    /** @var StorageRegistry $storageRegistry */
    $storageRegistry = Nanbando::get()->getService(StorageRegistry::class);
    $storageRegistry->register($name, $storage);

    return $storage;
}

task(
    'push-to',
    function (InputInterface $input, OutputInterface $output) {
        /** @var StorageCommunicator $communicator */
        $communicator = Nanbando::get()->getService(StorageCommunicator::class);
        $communicator->push(get('storage'), $input, $output);
    }
)->addArgument(
    new InputArgument('remote', InputArgument::REQUIRED, 'Name of the remote to push to.')
);

task(
    'fetch-from',
    function (InputInterface $input, OutputInterface $output) {
        /** @var StorageCommunicator $communicator */
        $communicator = Nanbando::get()->getService(StorageCommunicator::class);
        $communicator->fetch(get('storage'), $input, $output);
    }
)->addArgument(
    new InputArgument('remote', InputArgument::REQUIRED, 'Name of the remote to push to.')
)->addOption(
    new InputOption('file', null, InputOption::VALUE_REQUIRED, 'Name of the file to fetch.')
);

task(
    'list',
    function (InputInterface $input, OutputInterface $output) {
        /** @var StorageCommunicator $communicator */
        $communicator = Nanbando::get()->getService(StorageCommunicator::class);
        if ($input->getArgument('remote')) {
            $communicator->list($input, $output);

            return;
        }

        $communicator->listLocal(get('storage'), $input, $output);
    }
)->addArgument(
    new InputArgument('remote', InputArgument::REQUIRED, 'Name of the remote to push to.')
);
