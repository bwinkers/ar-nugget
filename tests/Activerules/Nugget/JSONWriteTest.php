<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class JSONWriteTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // All test will have these variables available to them under $this->
        $this->json = new \Activerules\Nugget\JSON();
        
        $object = new \stdClass();
        $object->name = 'Brian';
        $this->object = $object;
        
        $this->validJSON = '{"name":"Brian"}';
    }

    /**
     * Valid JSON should be returned
     */
    public function testWrittenFileIsValid()
    {
        $path = realpath('./tests/Activerules/Nugget/json/');
        
        $path = $path . '/writeTest.json';

        $this->json->write($this->object, $path);
        
        $result = $this->json->fromFile($path);

        $this->assertEquals($this->validJSON, json_encode($result));
    }
}
