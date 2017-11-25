<?php

namespace Webmaster777\BoardGameCollection\Test;

use DI\Container;

class IndexTest extends \PHPUnit\Framework\TestCase
{
  public function testIndexCreatesAContainer()
  {
    $container = require __DIR__.'/../index.php';

    $this->assertInstanceOf(Container::class, $container);

    return $container;
  }

  public function testLocalConfig()
  {
    $additional = <<<php
<?php

return [
  'myvar' => true
];
php;
    $localConfigPath = __DIR__ . '/../local-config.php';
    $this->assertNotFalse(
      file_put_contents($localConfigPath, $additional),
      "Unable to create additional-config file. Verify permissions"
    );


    $container = $this->testIndexCreatesAContainer();

    $this->assertTrue($container->get('myvar'));

    // cleanup
    unlink($localConfigPath);
  }
}
