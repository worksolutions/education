<?php

use WS\Education\Unit1\Task2\Server;

$server = new Server(10100);

$file = fopen(__DIR__.'/data/file.txt', 'r');
$server->registerHandler(function ($connection) use ($file) {

});