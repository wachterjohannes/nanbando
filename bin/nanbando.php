<?php

use Nanbando\Nanbando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require __DIR__ . '/../vendor/autoload.php';

$nanbando = Nanbando::get();

$input = $nanbando->getService(InputInterface::class);
$output = $nanbando->getService(OutputInterface::class);

$config = getcwd() . '/nanbando.php';
if ($input->hasParameterOption('--config', true)) {
    $config = $input->getParameterOption('--config');
}

require_once $config;

$application = $nanbando->boot();
$application = $application->run($input, $output);
