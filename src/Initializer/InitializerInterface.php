<?php

namespace Nanbando\Initializer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface InitializerInterface
{
    public function interact(InputInterface $input, OutputInterface $output): array;

    public function getTemplate(array $options): string;
}
