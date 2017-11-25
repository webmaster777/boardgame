<?php

namespace Webmaster777\BoardGameCollection\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;

class DoctrineCLITest extends TestCase
{
  public function testDoctrineCLIConfig()
  {
    $cliConfig = require __DIR__.'/../cli-config.php';
    $this->assertInstanceOf(HelperSet::class,$cliConfig);
  }
}
