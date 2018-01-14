<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetJSONTypeTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        // Use the Activerules dereferencer
        $dereferencer  = \Activerules\JsonReference\Dereferencer::draft4();
        
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
     * An object missing required fields should fail.
     */
    public function testInvalidTypeFails() 
    {
        $result = $this->nugget->jsonType('fooglesnaps');

        $this->assertEquals(false, $result);
    }
    
    /**
     * An object with valid core data should pass nugget validation
     */
    public function testValidTypePasses() 
    {
        $result = $this->nugget->jsonType('Text');

        $this->assertEquals('string', $result);
    }
}