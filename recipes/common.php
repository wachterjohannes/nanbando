<?php

namespace Nanbando;

require_once __DIR__ . '/basic/container.php';
require_once __DIR__ . '/basic/config.php';
require_once __DIR__ . '/backup/backup.php';

set('cwd', getcwd());
set('local', '%cwd%/var/backups');
