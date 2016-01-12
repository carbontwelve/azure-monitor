<?php

define('MONITOR_START', microtime(true));

//
// Register the composer autoloader
//
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require __DIR__ . '/../../vendor/autoload.php';
}

$monitor = new \Carbontwelve\AzureMonitor\Monitor();
$monitor->register(\Carbontwelve\AzureMonitor\Providers\CommandServiceProvider::class);
return $monitor;
