<?php

/**
 * this file should return an array, to be consumed by the
 * php-di container builder
 *
 * DO NOT EDIT THIS FILE.
 * If you want to add configuration, you should create a
 * local-config.php to add, replace or decorate the
 * configuration from this file.
 */

use function \DI\get;
use function \DI\object;
use function \DI\decorate;
use function \DI\factory;

return [
  // Doctrine ORM settings

  'doctrine.orm.connection' => [
    'driver' => 'pdo_sqlite',
    'path'   => __DIR__ . '/exampledb.db',
  ],

  'doctrine.orm.config' => function() {
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
      array(__DIR__."/src"), true, null, null, false);
    $config->setEntityNamespaces(['e'=>'\Webmaster777\BoardGameCollection\Entity']);
    return $config;
  },

  \Doctrine\ORM\EntityManager::class => factory([\Doctrine\ORM\EntityManager::class, 'create'])
    ->parameter('conn', get('doctrine.orm.connection'))
    ->parameter('config',get('doctrine.orm.config')),
  'doctrine.orm.helperset' => function( Interop\Container\ContainerInterface $container ) {
    $entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
    return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
  },

  // Slim app
  \Slim\App::class => function(\Psr\Container\ContainerInterface $container) {
    $app = new \Slim\App($container);

    $app->get('/',function(\Psr\Http\Message\ResponseInterface $response) {
      return $response->withStatus(501);
    });

    $app->get('/games', function(\Slim\Http\Response $response, \Doctrine\ORM\EntityManager $em) {

      $repository = $em->getRepository(\Webmaster777\BoardGameCollection\Entity\BoardGame::class);
      $result = $repository->findAll();

      return $response->withStatus(200)->withJson($result);
    });

    return $app;
  }


];
