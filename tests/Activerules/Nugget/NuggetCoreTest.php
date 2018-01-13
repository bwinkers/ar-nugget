<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetCoreTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        // Use the Activerules dereferencer
        $dereferencer  = \Activerules\JsonReference\Dereferencer::draft4();
        
        $this->nugget = new \Activerules\Nugget\Nugget();
        
        $this->nuggetSchema = $dereferencer->dereference('file://./Nugget.json');
        
        $this->missingCoreType = file_get_contents(__DIR__.'/objects/missingCoreType-Nugget.json');
        
        $this->validCoreType = file_get_contents(__DIR__.'/objects/validCoreType-Nugget.json');
        
        $this->invalidCoreType = file_get_contents(__DIR__.'/objects/invalidCoreType-Nugget.json');
        
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
    public function testMissingCoreDataFails() 
    {
        $result = $this->nugget->meetsSchema($this->missingCoreType, $this->nuggetSchema);

        $this->assertEquals(false, $result);
    }
    
    /**
     * An object with valid core data should pass nugget validation
     */
    public function testValidRequiredCoreDataPasses() 
    {
        $result = $this->nugget->meetsSchema($this->validCoreType, $this->nuggetSchema);

        $this->assertEquals(true, $result);
    }
   
    
    /**
     * An object with invalid core data should fail nugget validation
     */
    public function testInvalidRequiredCoreDataFails() 
    {
        $result = $this->nugget->meetsSchema($this->invalidCoreType, $this->nuggetSchema);

        $this->assertEquals(false, $result);
    }
}