<?php

namespace Webmaster777\BoardGameCollection\Test;

use Doctrine\ORM\EntityManager;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Webmaster777\BoardGameCollection\Entity\BoardGame;

class BoardGameTest extends SlimTestCase
{

  public function setUp()
  {
    parent::setUp();

    $em = $this->container->get(EntityManager::class);
    $em->createQuery('DELETE FROM e:BoardGame')->execute();
  }

  public function provideBoardGames() {
    return [
      ["Monopoly"],
      ["Risk"],
      ["Shetle'rs of Catan%20"],
    ];
  }

  /**
   * @param $name
   * @return BoardGame
   *
   * @dataProvider provideBoardGames
   */
  public function testNewBoardGameEntity($name)
  {
    $game = new BoardGame();
    $game->setName($name);

    $this->assertEquals($name, $game->getName());

    $em = $this->container->get(EntityManager::class);
    $em->persist($game);
    $em->flush($game);

    $this->assertNotNull($game->getId());
  }


  public function testPostInvalidNewGame() {
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "POST",
      "REQUEST_URI" => "/games/new",
    ]);
    $req = Request::createFromEnvironment($env);
    $this->container->set('request', $req);

    // get and run app
    $app = $this->container->get(App::class);
    $response = $app->run(true);

    // assert Bad Request http code
    $this->assertEquals(400, $response->getStatusCode(),
      "response code should be 400 - Bad Request");

  }

  /**
   * @param $name
   * @dataProvider provideBoardGames
   */
  public function testPostNewGame($name) {

    $response = $this->performPostRequest('/games/new',["name"=>$name]);

    // assert Created http code
    $this->assertEquals(201, $response->getStatusCode(),
      "response code should be 201 - Created");

    // assert we're receiving json
    $this->assertResponseContentType("application/json",$response);

    // verify actual content
    $decoded = json_decode(strval($response->getBody()));
    $this->assertTrue(is_object($decoded), "response is not an object");

    $this->assertEquals($name, $decoded->name);
  }

  public function testGetGames()
  {
    $response = $this->performGetRequest('/games');

    // assert not implemented http code
    $this->assertEquals(200, $response->getStatusCode(),
      "response code should be OK");

    // assert we're receiving json
    $this->assertResponseContentType("application/json",$response);

    $decoded = json_decode(strval($response->getBody()));
    $this->assertTrue(is_array($decoded), "response is not an array");
  }
}
