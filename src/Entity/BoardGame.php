<?php


namespace Webmaster777\BoardGameCollection\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BoardGame
 * @package Webmaster777\BoardGameCollection\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="boardgame")
 */
class BoardGame
{
  /**
   * @var integer
   * @ORM\Column(type="integer")
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $name;



  /**
   * @param string $name
   * @return BoardGame
   */
  public function setName(string $name): BoardGame
  {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }
}
