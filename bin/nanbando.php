<?php

use Nanbando\Console\Application;
use Nanbando\DependencyInjection\AddNanbandoInitializerPass;
use Nanbando\DependencyInjection\AddNanbandoScriptPass;
use Nanbando\DependencyInjection\AddNanbandoStoragePass;
use Nanbando\DependencyInjection\SetServicesPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Webmozart\PathUtil\Path;

require __DIR__ . '/../vendor/autoload.php';

$container = new ContainerBuilder();
$container->addCompilerPass(new AddConsoleCommandPass(ContainerCommandLoader::class));
$container->addCompilerPass(new AddNanbandoScriptPass());
$container->addCompilerPass(new AddNanbandoStoragePass());
$container->addCompilerPass(new AddNanbandoInitializerPass());
$container->addCompilerPass(new SetServicesPass());

$container->setParameter('cwd', getcwd());

$locator = new FileLocator(__DIR__ . '/../config');
$loader = new XmlFileLoader($container, $locator);
$loader->load('services.xml');

$backupFile = Path::join([getcwd(), 'backup.php']);
if (file_exists($backupFile)) {
    $loader = new PhpFileLoader($container, new FileLocator(getcwd()));
    $loader->load('backup.php');
}

$container->compile(true);

/** @var Application $application */
$application = $container->get('application');
$application = $application->run($container->get('input'), $container->get('output'));
