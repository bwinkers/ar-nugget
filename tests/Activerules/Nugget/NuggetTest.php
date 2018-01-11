<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        // Use the League dereferencer
        $dereferencer  = \Activerules\JsonReference\Dereferencer::draft4();
        
        // A known valid Person object, it has a name property.
        $this->validPerson = '{"name":"Brian"}';
        
        // A known invalid Person object, it has NO name property.
        $this->invalidPerson = '{"noName":"Brian"}';
        
        // A known valid Person/Address object, it has a name property and the address has a type.
        $this->validPersonAddress = '{"name":"Brian","address": {"type": "street"} }';
        
        
        // A known valid Person/Address object, it has a name property and the address has a type.
        $this->invalidPersonAddressEnum = '{"name":"Brian","address": {"type": "notstreet"} }';
        // A known valid Person/Address object, it has a name property and the address has a type.
        $this->invalidPersonAddress = '{"name":"Brian","address": {"notype":"street"}}';
      
        // All test will have these variables available to them under $this->
        $this->nugget = new \Activerules\Nugget\Nugget();
        $this->localPersonSchema = $dereferencer->dereference('file://' . __DIR__ . '/schema/person.json');
        
        // NOTE: NO REMOTE SCHEMA TESTS IN THIS FILE!
        // This test gets run by Travis CI and for some as of yet unresolved reason it does not work with remote schema.
        //$this->remotePersonSchema = $dereferencer->dereference('https://rawgit.com/bwinkers/nugget/master/tests/Activerules/Nugget/schema/person.json');
    }

    /**
     * This gets called after each test function
     */
    public function tearDown() 
    {
        //$this->myClass = null;
    }
  
    /**
     * A known valid schema should pass validation
     */
    public function testValidDataPasses() 
    {
        $result = $this->nugget->meetsSchema($this->validPerson, $this->localPersonSchema);

        $this->assertEquals(true, $result);
    }
    
    /**
     * A known invalid schema should fail validation
     */
    public function testInvalidDataFails() 
    {
        $result = $this->nugget->meetsSchema($this->invalidPerson, $this->localPersonSchema);

        $this->assertEquals(false, $result);
    }

}