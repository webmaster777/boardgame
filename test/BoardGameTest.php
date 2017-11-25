<?php

namespace Webmaster777\BoardGameCollection\Test;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Webmaster777\BoardGameCollection\Entity\BoardGame;

class BoardGameTest extends TestCase
{

  /**
   * @var ContainerInterface
   */
  protected $container;

  public function setUp()
  {
    parent::setUp();
    $this->container = require __DIR__.'/../index.php';

    $em = $this->container->get(EntityManager::class);
    $em->createQuery('DELETE FROM r:BoardGame')->execute();
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

}
