<?php

use Nanbando\Console\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

require __DIR__ . '/../vendor/autoload.php';

$container = new ContainerBuilder();
$container->addCompilerPass(new AddConsoleCommandPass(ContainerCommandLoader::class));

$locator = new FileLocator(__DIR__ . '/../config');
$loader = new XmlFileLoader($container, $locator);
$loader->load('services.xml');

$container->compile(true);

/** @var Application $application */
$application = $container->get('application');
$application = $application->run($container->get('input'), $container->get('output'));
