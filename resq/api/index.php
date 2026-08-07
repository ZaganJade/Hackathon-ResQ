<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Surface fatal errors in the response body instead of a bare empty 500, so
// they show up in `curl`/the browser the same way Laravel's own Whoops page
// would. Tied to APP_DEBUG so it turns itself off in real production.
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// This entrypoint only runs on Vercel (Docker/k8s use public/index.php
// instead), whose deployment filesystem is read-only outside /tmp. The
// deployed bootstrap/cache/*.php files also reflect whatever machine last
// ran `composer install` locally (dev packages, absolute paths) rather than
// the runtime's actual --no-dev install, so config/services/packages/routes
// caches are pointed at fresh files in /tmp instead of trusting those.
@mkdir('/tmp/bootstrap/cache', 0775, true);
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes-v7.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Laravel's writable paths (compiled views, logs, the local session/cache
// driver, ...) are always redirected to /tmp for the same read-only-fs
// reason above.
$storagePath = '/tmp/storage';

foreach ([
    'app/public',
    'framework/cache/data',
    'framework/sessions',
    'framework/testing',
    'framework/views',
    'logs',
] as $dir) {
    @mkdir("{$storagePath}/{$dir}", 0775, true);
}

$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
