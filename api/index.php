<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Bypass sistem file Read-Only Vercel dengan menggunakan folder sementara (/tmp)
$storagePath = '/tmp/storage';

if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath . '/framework/cache', 0777, true);
    mkdir($storagePath . '/framework/views', 0777, true);
    mkdir($storagePath . '/framework/sessions', 0777, true);
    mkdir($storagePath . '/logs', 0777, true);
}

$app->useStoragePath($storagePath);
$_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

$app->handleRequest(Request::capture());
