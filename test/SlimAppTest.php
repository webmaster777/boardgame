<?php

namespace Webmaster777\BoardGameCollection\Test;

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;

class SlimAppTest extends TestCaseWithContainer
{
  public function testNotImplementedHome()
  {
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "GET",
      "REQUEST_URI" => "/",
    ]);
    $req = Request::createFromEnvironment($env);
    $this->container->set('request', $req);

    // get and run app
    $app = $this->container->get(App::class);
    $response = $app->run(true);

    // assert not implemented http code
    $this->assertEquals(501, $response->getStatusCode());
  }

  public function testGetGames()
  {
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "GET",
      "REQUEST_URI" => "/games",
      "HTTP_ACCEPT" => "application/json;q=0.9,*/*;q=0.8"
    ]);
    $req = Request::createFromEnvironment($env);
    $this->container->set('request', $req);

    // get and run app
    $app = $this->container->get(App::class);
    $response = $app->run(true);

    // assert not implemented http code
    $this->assertEquals(200, $response->getStatusCode(),
      "response code should be OK");

    // verify we're receiving json
    $this->assertTrue($response->hasHeader("Content-type"));
    $header = $response->getHeader("Content-type");
    $header = array_shift($header);
    $header = explode(";",$header);
    $header = array_shift($header);
    $this->assertEquals("application/json",$header,
      "response is not json");

    $decoded = json_decode(strval($response->getBody()));
    $this->assertTrue(is_array($decoded), "response is not an array");
  }
}
