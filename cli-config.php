<?php

/**
 * This file is required to get the doctrine cli working.
 *
 * All the magic happens in the
 * container config (config.php)
 */

$container = require 'index.php';
return $container->get('doctrine.orm.helperset');
