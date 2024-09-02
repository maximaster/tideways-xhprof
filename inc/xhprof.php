<?php

declare(strict_types=1);

use Maximaster\TidewaysXhprof\TidewaysXhprofPersister;
use Maximaster\TidewaysXhprof\TidewaysXhprofSession;

require_once __DIR__ . '/../src/Contract/TidewaysXhprofPersisterInterface.php';
require_once __DIR__ . '/../src/Contract/TidewaysXhprofSessionInterface.php';
require_once __DIR__ . '/../src/Exception/TidewaysXhprofPersistException.php';
require_once __DIR__ . '/../src/TidewaysXhprofPersister.php';
require_once __DIR__ . '/../src/TidewaysXhprofSession.php';

$env = static fn (string $parameter) => getenv($parameter) ?: $_ENV[$parameter] ?? false;

$triggerName = strval($env('MAXIMASTER_TIDEWAYS_XHPROF_TRIGGER_NAME') ?: 'XHPROF');
if ($triggerName === '') {
    return;
}

$triggerValue = strval($env('MAXIMASTER_TIDEWAYS_XHPROF_TRIGGER_VALUE') ?: '');
if ($triggerValue === '') {
    return;
}

$shouldStartProfiling = false;
foreach (['_SERVER', '_GET', '_POST', '_REQUEST', '_ENV', '_COOKIE', '_SESSION'] as $triggerContainerName) {
    if (
        $triggerContainerName === '_SESSION'
        && $env('MAXIMASTER_TIDEWAYS_XHPROF_USE_SESSION_TRIGGER')
        && session_status() !== PHP_SESSION_ACTIVE
    ) {
        session_start();
    }

    if (array_key_exists($triggerName, $GLOBALS[$triggerContainerName] ?? []) === false) {
        continue;
    }

    if ($GLOBALS[$triggerContainerName][$triggerName] === $triggerValue) {
        $shouldStartProfiling = true;
        break;
    }
}

if ($shouldStartProfiling === false) {
    return;
}

$xhprofPersister = new TidewaysXhprofPersister(
    $env('MAXIMASTER_TIDEWAYS_XHPROF_DIRECTORY') ?: getcwd(),
    $env('MAXIMASTER_TIDEWAYS_XHPROF_FILENAME_FORMAT') ?: '#date#.#uniqid#.xhprof',
    $env('MAXIMASTER_TIDEWAYS_XHPROF_DATETIME_FORMAT') ?: 'Ymdhis'
);

$xhprofSession = new TidewaysXhprofSession($xhprofPersister);

$xhprofSession->start(intval($env('MAXIMASTER_TIDEWAYS_XHPROF_FLAGS') ?: 0));

register_shutdown_function(function () use ($xhprofSession) {
    $xhprofSession->stop();
});
