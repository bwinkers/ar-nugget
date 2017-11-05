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
        $dereferencer  = \League\JsonReference\Dereferencer::draft4();
        
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
        $this->remotePersonSchema = $dereferencer->dereference('https://rawgit.com/bwinkers/nugget/master/tests/Activerules/Nugget/schema/person.json');
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
        $result = $this->nugget->isValid($this->validPerson, $this->localPersonSchema);

        $this->assertEquals(true, $result);
    }
    
    /**
     * A known invalid schema should fail validation
     */
    public function testInvalidDataFails() 
    {
        $result = $this->nugget->isValid($this->invalidPerson, $this->localPersonSchema);

        $this->assertEquals(false, $result);
    }
    
    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testValidDataPassesRemoteSchema() 
    {
        $result = $this->nugget->isValid($this->validPerson, $this->remotePersonSchema);

        $this->assertEquals(true, $result);
    }
    
    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testInvalidDataFailsRemoteSchema() 
    {
        $result = $this->nugget->isValid($this->invalidPerson, $this->remotePersonSchema);

        $this->assertEquals(false, $result);
    }
    
    /**
     * A valid object should pass a referenced remote schema validation
     */
    public function testValidDataPassesReferencedSchema() 
    {
        $result = $this->nugget->isValid($this->validPersonAddress, $this->localPersonSchema);

        $this->assertEquals(true, $result);
    }
    
    /**
     * An invalid object should pass a referenced remote schema validation
     */
    public function testInvalidDataFailsReferencedSchema() 
    {
        $result = $this->nugget->isValid($this->invalidPersonAddress, $this->localPersonSchema);

        $this->assertEquals(false, $result);
    }
    
    /**
     * A valid object should pass a referenced remote schema validation
     */
    public function testStringNotInEnumFails() 
    {
        $result = $this->nugget->isValid($this->invalidPersonAddressEnum, $this->localPersonSchema);

        $this->assertEquals(false, $result);
    }

}