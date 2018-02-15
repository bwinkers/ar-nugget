<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class JSONFromFileTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // All test will have these variables available to them under $this->
        $this->json = new \Activerules\Nugget\JSON();
        $this->validJSON = '{"name":"Brian"}';
    }

    /**
     * Valid JSON should be returned
     */
    public function testReturnsValidJSONFromFile()
    {
        $path = realpath('./tests/Activerules/Nugget/json/valid.json');
    
        $result = $this->json->fromFile($path);

        $this->assertEquals($this->validJSON, json_encode($result));
    }

    /**
     * Invalid JSON should return false
     */
    public function testReturnsFalseForNonJSONFile()
    {
        $path = realpath('./tests/Activerules/Nugget/json/invalid.json');
        
        $result = $this->json->fromFile($path);

        $this->assertEquals(false, $result);
    }
}
