<?php

namespace Nanbando;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
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
        return $this->container->getParameterBag();
    }

    public function getService(string $id)
    {
        return $this->container->get($id);
    }
}
