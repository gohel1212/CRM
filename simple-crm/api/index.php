<?php

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
