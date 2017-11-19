<?php

namespace Nanbando;

require_once __DIR__ . '/basic/config.php';
require_once __DIR__ . '/basic/host.php';
require_once __DIR__ . '/connection/ssh.php';
require_once __DIR__ . '/task/task.php';
require_once __DIR__ . '/basic/backup.php';
require_once __DIR__ . '/basic/storage.php';

set('temp', '%cwd%/var/temp');
set('storage', '%cwd%/var/backups');
