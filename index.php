<?php

/**
 * main entry of the application, returns the container
 */

// Get autoloader
require_once __DIR__.'/vendor/autoload.php';

// Create ContainerBuilder
$builder = new \DI\ContainerBuilder();

// Add default php-di bridge Slim config
$builder->addDefinitions(require __DIR__ . '/vendor/php-di/slim-bridge/src/config.php');

// Add base config (default settings)
$baseConfig = __DIR__.'/config.php';
$baseConfig = require $baseConfig;
$builder->addDefinitions($baseConfig);

// Add any additional config (local configuration)
$additionalConfig = __DIR__.'/local-config.php';
if(file_exists($additionalConfig)) {
  $additionalConfig = require $additionalConfig;
  $builder->addDefinitions($additionalConfig);
}

return $builder->build();
