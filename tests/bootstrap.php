<?php

require_once __DIR__ . '/../recipes/common.php';

$file = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$loader = require $file;

return $loader;
