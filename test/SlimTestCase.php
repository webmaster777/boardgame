<?php


namespace Webmaster777\BoardGameCollection\Test;

use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;

abstract class SlimTestCase extends TestCase
{
  /**
   * @var ContainerInterface|Container
   */
  protected $container;

  public function setUp()
  {
    parent::setUp();
    $this->container = require __DIR__.'/../index.php';
  }

  /**
   * @param $path
   * @param array $parameters
   * @param string $accept
   * @return \Psr\Http\Message\ResponseInterface
   */
  protected function performGetRequest($path, $parameters = [], $accept = "application/json") : ResponseInterface
  {
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "GET",
      "REQUEST_URI" => $path,
    ]);
    $req = Request::createFromEnvironment($env)
      ->withHeader("Accept",$accept)
      ->withQueryParams($parameters);

    return $this->performRequest($req);
  }

  /**
   * @param $path
   * @param array $parameters
   * @param string $accept
   * @return \Psr\Http\Message\ResponseInterface
   */
  protected function performPostRequest($path, $parameters = [], $accept = "application/json") : ResponseInterface
  {
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "POST",
      "REQUEST_URI" => $path,
    ]);
    $req = Request::createFromEnvironment($env)
      ->withHeader("Accept",$accept)
      ->withParsedBody($parameters);

    return $this->performRequest($req);
  }

  /**
   * @param $path
   * @param array $parameters
   * @param string $accept
   * @return \Psr\Http\Message\ResponseInterface
   */
  protected function performPutRequest($path, $parameters = [], $accept = "application/json") : ResponseInterface
  {
    // create mock request
    $env = Environment::mock([
      "REQUEST_METHOD" => "POST",
      "REQUEST_URI" => $path,
    ]);
    $req = Request::createFromEnvironment($env)
      ->withHeader("Accept",$accept)
      ->withParsedBody($parameters);

    return $this->performRequest($req);
  }

  protected function assertResponseContentType($expectedType, ResponseInterface $response)
  {
    // verify we're receiving json
    $this->assertTrue($response->hasHeader("Content-type"),
      "Content-type header missing from response");

    // shift first content-type header, split on ; take first part.
    $header = $response->getHeader("Content-type");
    $header = array_shift($header);
    $header = explode(";",$header);
    $header = array_shift($header);

    $this->assertEquals($expectedType, $header,
      "response is not of type $expectedType");
  }

  /**
   * @param $req
   * @return ResponseInterface
   */
  protected function performRequest($req): ResponseInterface
  {
    // Set the request container
    $this->container->set('request', $req);

    // get and run app
    $app = $this->container->get(App::class);
    return $app->run(true);
  }
}
