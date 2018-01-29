<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetLoadPropertyFileTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        // All test will have these variables available to them under $this->
        $this->nugget = new \Activerules\Nugget\Nugget();
    }

    /**
     * This gets called after each test function
     */
    public function tearDown() 
    {
        //$this->myClass = null;
    }
    
    
    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testLoadedPropertyFileIsValid() 
    {
        $propFile = realpath(__DIR__.'/properties/test.json');
        
        $result = $this->nugget->loadPropertyFile($propFile);
        var_dump($result);
        $this->assertEquals('property', $result->test);
    }

}