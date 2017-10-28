<?php

namespace Nanbando\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class MysqlDumpPlugin implements PluginInterface
{
    public static function create(string $host, string $userName, string $database): self
    {
        return new self();
    }

    public static function autoConfigure(ParameterBag $parameter): self
    {
        return new self();
    }

    public function backup(BackupContext $context, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('mysql');
    }
}
