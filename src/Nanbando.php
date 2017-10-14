<?php

namespace Nanbando;

use Nanbando\Console\Application;
use Nanbando\Console\TaskCommandFactory;
use Nanbando\Task\TaskRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Nanbando
{
    /**
     * @var self
     */
    private static $instance;

    public static function get(): self
    {
        if (!self::$instance) {
            return self::$instance = new Nanbando();
        }

        return self::$instance;
    }

    public static function reset()
    {
        self::$instance = null;
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ParameterBag
     */
    private $parameterBag;

    public function __construct()
    {
        $this->container = new ContainerBuilder();

        $this->import(__DIR__ . '/../Resources/config/services.yaml');
    }

    public function import(string $file): self
    {
        $locator = new FileLocator(dirname($file));

        $loaderResolver = new LoaderResolver([new YamlFileLoader($this->container, $locator)]);
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        $delegatingLoader->load($file);

        return $this;
    }

    public function getParameterBag(): ParameterBag
    {
        if (!$this->container->isCompiled()) {
            return $this->container->getParameterBag();
        }

        return $this->parameterBag;
    }

    public function getService(string $id)
    {
        if (!$this->container->isCompiled()) {
            $this->container->compile();
            $this->parameterBag = new EnvPlaceholderParameterBag($this->container->getParameterBag()->all());
        }

        return $this->container->get($id);
    }

    public function boot()
    {
        $taskCommandFactory = $this->getService(TaskCommandFactory::class);

        $commands = [];
        foreach ($this->getService(TaskRegistry::class)->getTasks() as $name => $task) {
            $commands[] = $taskCommandFactory->create($name, $task);
        }

        $application = new Application('Nanbando');
        $application->addCommands($commands);

        return $application;
    }
}
