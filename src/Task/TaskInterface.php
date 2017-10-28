<?php

namespace Nanbando\Task;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

interface TaskInterface
{
    public function setDescription(string $description): self;

    public function getDescription(): ?string;

    public function addOption(InputOption $option): self;

    public function getOptions(): array;

    public function addArgument(InputArgument $argument): self;

    public function getArguments(): array;

    public function invoke(array $parameter = []);

    public function getParameter(): array;

    public function setParameter(array $parameter): self;

    public function before(callable $callable, array $parameter = []): self;

    public function after(callable $callable, array $parameter = []): self;
}
