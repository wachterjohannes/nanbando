<?php

namespace Nanbando\Task;

interface TaskInterface
{
    public function invoke(array $parameter = []);

    public function getParameter(): array;

    public function setParameter(array $parameter): self;

    public function before(callable $callable, array $parameter = []): self;

    public function after(callable $callable, array $parameter = []): self;
}
