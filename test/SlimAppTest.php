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
}
