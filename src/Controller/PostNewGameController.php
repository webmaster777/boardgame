<?php


namespace Webmaster777\BoardGameCollection\Controller;


use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Webmaster777\BoardGameCollection\Entity\BoardGame;

class PostNewGameController
{
  public function __invoke(Request $request, Response $response, EntityManager $em)
  {
    $name = $request->getParam("name");
    if(!$name) {
      return $response->withStatus(400)->withJson([
        "code"=>400,
        "message"=>"Bad Request: missing `name`",
      ]);
    }

    $game = new BoardGame();
    $game->setName($name);

    $em->persist($game);
    $em->flush($game);

    return $response->withStatus(201)->withJson($game);
  }
}
