<?php

namespace Webmaster777\BoardGameCollection\Test;

class SlimAppTest extends SlimTestCase
{
  public function testNotImplementedHome()
  {
    $response = $this->performGetRequest("/");

    // assert not implemented http code
    $this->assertEquals(501, $response->getStatusCode());
  }
}
