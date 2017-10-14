<?php

namespace Nanbando;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once 'recipes/common.php';

set('storage', '%cwd%/var/backups');

host('asapo.at')
    ->setDirectory('/var/www/asapo.at')
    ->setUser('johannes');

registerTask(
    'blabla',
    function (InputInterface $input, OutputInterface $output) {
        $output->writeln('HELLO');
    }
);
