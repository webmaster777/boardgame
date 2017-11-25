<?php

/** @var \Psr\Container\ContainerInterface $container */
$container = require __DIR__.'/../index.php';

$app = $container->get(\Slim\App::class);
$app->run();
