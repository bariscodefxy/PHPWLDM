<?php

require_once "../vendor/autoload.php";

use Elbion\Engine;

$engine = new Engine();
$engine->setViewDir("views/");
$engine->setCacheDir("cache/");
$engine->setExt(".elb");
$engine->loadFile("hello_world");