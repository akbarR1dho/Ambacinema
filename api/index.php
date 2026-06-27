<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

    $app = require_once __DIR__.'/../bootstrap/app.php';

    $storagePath = '/tmp/storage';

    if (!is_dir($storagePath)) {
        @mkdir($storagePath, 0777, true);
        @mkdir($storagePath . '/framework/cache', 0777, true);
        @mkdir($storagePath . '/framework/views', 0777, true);
        @mkdir($storagePath . '/framework/sessions', 0777, true);
        @mkdir($storagePath . '/logs', 0777, true);
        @mkdir($storagePath . '/bootstrap/cache', 0777, true);
    }

    $app->useStoragePath($storagePath);
    
    $_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
    putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');
    
    $_ENV['APP_SERVICES_CACHE'] = $storagePath . '/bootstrap/cache/services.php';
    putenv('APP_SERVICES_CACHE=' . $storagePath . '/bootstrap/cache/services.php');
    
    $_ENV['APP_PACKAGES_CACHE'] = $storagePath . '/bootstrap/cache/packages.php';
    putenv('APP_PACKAGES_CACHE=' . $storagePath . '/bootstrap/cache/packages.php');
    
    $_ENV['APP_CONFIG_CACHE'] = $storagePath . '/bootstrap/cache/config.php';
    putenv('APP_CONFIG_CACHE=' . $storagePath . '/bootstrap/cache/config.php');
    
    $_ENV['APP_ROUTES_CACHE'] = $storagePath . '/bootstrap/cache/routes-v7.php';
    putenv('APP_ROUTES_CACHE=' . $storagePath . '/bootstrap/cache/routes-v7.php');
    
    $_ENV['APP_EVENTS_CACHE'] = $storagePath . '/bootstrap/cache/events.php';
    putenv('APP_EVENTS_CACHE=' . $storagePath . '/bootstrap/cache/events.php');

    $app->handleRequest(Request::capture());
