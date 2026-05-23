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

// Require the Laravel frontend bootstrap index file
require __DIR__ . '/../public/index.php';
