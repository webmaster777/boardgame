<?php


namespace Webmaster777\BoardGameCollection\Controller;


class GetGamesController
{
  public function __invoke(\Slim\Http\Response $response, \Doctrine\ORM\EntityManager $em) {
    $repository = $em->getRepository(\Webmaster777\BoardGameCollection\Entity\BoardGame::class);
    $result = $repository->findAll();

    return $response->withStatus(200)->withJson($result);
  }
}
