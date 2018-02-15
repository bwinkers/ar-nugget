<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class JSONValidTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // All test will have these variables available to them under $this->
        $this->json = new \Activerules\Nugget\JSON();

        $this->validJSON = '{"name":"Brian"}';
        $this->inValidJSON = 'name":"Brian"';
    }

    /**
     * Valid JSON should be returned
     */
    public function testReturnsValidJSON()
    {
        $result = $this->json->valid($this->validJSON);

        $this->assertEquals(json_decode($this->validJSON), $result);
    }

    /**
     * Invalid JSON should return false
     */
    public function testReturnsFalseForNonJSON()
    {
        $result = $this->json->valid($this->inValidJSON);

        $this->assertEquals(false, $result);
    }
}
