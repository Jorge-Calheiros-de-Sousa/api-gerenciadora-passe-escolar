<?php

use Dotenv\Dotenv;
use Jorge\ReabastecimentoDoCartao\Routes;


require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Routes::init();
