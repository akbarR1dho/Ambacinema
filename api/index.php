<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    require __DIR__.'/../vendor/autoload.php';

    $app = require_once __DIR__.'/../bootstrap/app.php';

    $storagePath = '/tmp/storage';

    if (!is_dir($storagePath)) {
        @mkdir($storagePath, 0777, true);
        @mkdir($storagePath . '/framework/cache', 0777, true);
        @mkdir($storagePath . '/framework/views', 0777, true);
        @mkdir($storagePath . '/framework/sessions', 0777, true);
        @mkdir($storagePath . '/logs', 0777, true);
    }

    $app->useStoragePath($storagePath);
    $_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
    putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Vercel PHP Fatal Error</h1>";
    echo "<pre>" . (string) $e . "</pre>";
}
