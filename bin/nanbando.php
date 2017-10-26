<?php

use Nanbando\Console\Application;
use Nanbando\Nanbando;

require __DIR__ . '/../vendor/autoload.php';

$nanbando = Nanbando::get();
$application = $nanbando->getService(Application::class);

require_once $application->getConfig();

$application->initialize();
$application = $application->run();
