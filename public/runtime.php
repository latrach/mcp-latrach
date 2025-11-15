<?php

use Symfony\Component\Runtime\SymfonyRuntime;

$_SERVER['APP_RUNTIME'] = SymfonyRuntime::class;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

