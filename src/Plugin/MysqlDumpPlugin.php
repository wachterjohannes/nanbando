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
        return new MysqlDumpPlugin();
    }

    public static function autoConfigure(ParameterBag $parameter): self
    {
        return new MysqlDumpPlugin();
    }

    public function backup(BackupContext $context, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('mysql');
    }
}
