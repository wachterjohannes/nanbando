<?php

namespace Nanbando\Tests\Recipes\Basic;

use function Nanbando\get;
use Nanbando\Nanbando;
use function Nanbando\set;
use Nanbando\Tests\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use function Nanbando\import;
use function Nanbando\parameters;

class ConfigTest extends TestCase
{
    public function testParameters()
    {
        $host = parameters();
        $this->assertInstanceOf(ParameterBag::class, $host);
    }

    public function testImport()
    {
        import(__DIR__ . '/../../Resources/config/parameters.yaml');

        $this->assertEquals('123', get('test'));
    }

    public function testSet()
    {
        set('test-set', '123');

        $parameterBag = Nanbando::get()->getParameterBag();
        $this->assertEquals('123', $parameterBag->get('test-set'));
    }

    public function testGet()
    {
        $parameterBag = Nanbando::get()->getParameterBag();
        set('test-get', '123');

        $this->assertEquals('123', $parameterBag->get('test-get'));
    }

    public function testGetDefaultNull()
    {
        $this->assertNull(get('test-get-default'));
    }

    public function testGetDefault()
    {
        $this->assertEquals('123', get('test-get-default', '123'));
    }
}
