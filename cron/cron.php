<?php

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use Ector\Checker\Checker;

$checker = new Checker();
$checker->check();

echo "OK\n";
