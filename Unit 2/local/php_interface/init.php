<?php
CModule::IncludeModule('ws.tools');

$wsTools = WS\Tools\Module::getInstance();
$wsTools->classLoader()->configure(
    array(
        "psr4" => array(
            "Domain\\" => __DIR__ . DIRECTORY_SEPARATOR . "classes",
        )
    )
);
$config = include __DIR__ .'/config.php';
$wsTools->config($config);
