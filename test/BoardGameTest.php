<?php

namespace Webmaster777\BoardGameCollection\Test;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Webmaster777\BoardGameCollection\Entity\BoardGame;

class BoardGameTest extends TestCaseWithContainer
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
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "POST",
      "REQUEST_URI" => "/games/new",
    ]);
    $req = Request::createFromEnvironment($env)
      ->withParsedBody(["name"=>$name]);
    $this->container->set('request', $req);

    // get and run app
    $app = $this->container->get(App::class);
    $response = $app->run(true);

    // assert Created http code
    $this->assertEquals(201, $response->getStatusCode(),
      "response code should be 201 - Created");

    // verify we're receiving json
    $this->assertTrue($response->hasHeader("Content-type"));
    $header = $response->getHeader("Content-type");
    $header = array_shift($header);
    $header = explode(";",$header);
    $header = array_shift($header);
    $this->assertEquals("application/json",$header,
      "response is not json");

    // verify actual content
    $decoded = json_decode(strval($response->getBody()));
    $this->assertTrue(is_object($decoded), "response is not an object");

    $this->assertEquals($name, $decoded->name);
  }
}
