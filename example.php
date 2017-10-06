<?php

namespace Nanbando;

use Nanbando\Plugin\DirectoryPlugin;
use Nanbando\Plugin\MysqlDumpPlugin;

require_once 'recipes/common.php';

import('app/config/paramters.yml');
set('storage', '%pwd%/var/backups');

host('asapo.at')
    ->setDirectory('/var/www/asapo.at')
    ->setUser('johannes');

attach('uploads', DirectoryPlugin::create(get('%pwd%/var/uploads')));
attach('database', MysqlDumpPlugin::autoConfigure(parameters()));
