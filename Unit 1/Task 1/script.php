<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = include __DIR__.'/vendor/autoload.php';
$loader->addPsr4("WS\Education\Unit1\Task1\\", __DIR__."/lib/");

