<?php

use Symfony\Component\Console\Application;

$app = require_once __DIR__ . '/src/bootstrap.php';

/** @var \Symfony\Component\Console\Application $cli */
$cli = $app[Application::class];
$cli->run();
