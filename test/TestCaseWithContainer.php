<?php


namespace Webmaster777\BoardGameCollection\Test;

use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class TestCaseWithContainer extends TestCase
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
}
