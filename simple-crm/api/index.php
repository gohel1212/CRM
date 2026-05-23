<?php

// Enable fatal error display but suppress deprecation warnings
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

// Vercel serverless workaround: Create storage folders in writeable /tmp directory
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/bootstrap/cache'
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Redirect Laravel bootstrap cache files to writeable /tmp directory
$bootstrapCacheDir = '/tmp/storage/bootstrap/cache';
$_ENV['APP_SERVICES_CACHE'] = $bootstrapCacheDir . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $bootstrapCacheDir . '/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $bootstrapCacheDir . '/config.php';
$_ENV['APP_ROUTES_CACHE'] = $bootstrapCacheDir . '/routes.php';
$_ENV['APP_EVENTS_CACHE'] = $bootstrapCacheDir . '/events.php';

putenv("APP_SERVICES_CACHE=" . $_ENV['APP_SERVICES_CACHE']);
putenv("APP_PACKAGES_CACHE=" . $_ENV['APP_PACKAGES_CACHE']);
putenv("APP_CONFIG_CACHE=" . $_ENV['APP_CONFIG_CACHE']);
putenv("APP_ROUTES_CACHE=" . $_ENV['APP_ROUTES_CACHE']);
putenv("APP_EVENTS_CACHE=" . $_ENV['APP_EVENTS_CACHE']);

// Require the Laravel frontend bootstrap index file
require __DIR__ . '/../public/index.php';
