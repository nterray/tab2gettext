#!/usr/bin/env php
<?php
/**
 * Copyright (c) Enalean, 2019. All Rights Reserved.
 */

require_once 'vendor/autoload.php';

use Composer\XdebugHandler\XdebugHandler;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Tab2Gettext\Tab2Gettext;

$xdebug = new XdebugHandler('tab2gettext');
$xdebug->check();
unset($xdebug);

$log = new Logger('log');
$log->pushHandler(new ErrorLogHandler());

try {
    $reflector = new Tab2Gettext($log);

    if (! isset($argv[1]) && ! is_dir($argv[1])) {
        throw new RuntimeException("Please provide a directory as first parameter");
    }
    $filepath = $argv[1];

    if (! isset($argv[2])) {
        throw new RuntimeException("Please provide a primary key as second parameter");
    }
    $primarykey = $argv[2];

    if (! isset($argv[3])) {
        throw new RuntimeException("Please provide a domain as third parameter");
    }
    $domain = $argv[3];

    if (! isset($argv[4]) && ! is_file($argv[4])) {
        throw new RuntimeException("Please provide a cache lang path as fourth parameter");
    }
    $cachelangpath = $argv[4];

    $reflector->run($filepath, $primarykey, $domain, $cachelangpath);
} catch (Exception $exception) {
    $log->critical($exception->getMessage());
    exit(1);
}
