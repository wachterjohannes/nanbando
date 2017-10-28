<?php

namespace Nanbando\Task;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Task implements TaskInterface
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var InputOption[]
     */
    private $options = [];

    /**
     * @var InputArgument[]
     */
    private $arguments = [];

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var mixed[]
     */
    private $parameter;

    /**
     * @var callable[]
     */
    private $beforeChain = [];

    /**
     * @var callable[]
     */
    private $afterChain = [];

    public function __construct(callable $callable, array $parameter = [])
    {
        $this->callable = $callable;
        $this->parameter = $parameter;
    }

    public function getCallable(): callable
    {
        return $this->callable;
    }

    public function setDescription(string $description): TaskInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function addOption(InputOption $option): TaskInterface
    {
        $this->options[] = $option;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function addArgument(InputArgument $argument): TaskInterface
    {
        $this->arguments[] = $argument;

        return $this;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getParameter(): array
    {
        return $this->parameter;
    }

    public function setParameter(array $parameter): TaskInterface
    {
        $this->parameter = $parameter;

        return $this;
    }

    public function before(callable $callable, array $parameter = []): TaskInterface
    {
        if (0 === count($parameter)) {
            $parameter = [$this];
        }

        $this->beforeChain[] = [$callable, $parameter];

        return $this;
    }

    public function after(callable $callable, array $parameter = []): TaskInterface
    {
        if (0 === count($parameter)) {
            $parameter = [$this];
        }

        $this->afterChain[] = [$callable, $parameter];

        return $this;
    }

    public function invoke(array $parameter = [])
    {
        $this->invokeChain($this->beforeChain);
        $result = call_user_func_array($this->callable, array_merge($this->parameter, $parameter));
        $this->invokeChain($this->afterChain);

        return $result;
    }

    protected function invokeChain(array $chain)
    {
        foreach ($chain as $item) {
            call_user_func_array($item[0], $item[1]);
        }
    }
}
