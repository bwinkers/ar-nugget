<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetLimitToSchemaTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        // Use the Activerules dereferencer
        $dereferencer  = \Activerules\JsonReference\Dereferencer::draft4();
        
        $validPersonArray = ['name'=>'Brian'];
        $this->validPerson = json_encode($validPersonArray);
        
        $dirtyPersonArray = array_merge($validPersonArray, array('lorem'=>array('remove'=>'me'), 'ipsum'=>'More BAD text to remove.'));
        $this->dirtyPerson = json_encode($dirtyPersonArray);

        // All test will have these variables available to them under $this->
        $this->nugget = new \Activerules\Nugget\Nugget();
        $this->personSchema = $dereferencer->dereference('file://' . __DIR__ . '/objects/person.json');
        //$this->personSchema = $dereferencer->dereference('https://rawgit.com/bwinkers/nugget/master/tests/Activerules/Nugget/objects/person.json'); 
        
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
    public function testLimitingToSchema() 
    {
        $cleanPerson = $this->nugget->limitToSchema($this->dirtyPerson, $this->personSchema);
        
        $this->assertEquals($cleanPerson, $this->validPerson);
        
        $result = $this->nugget->meetsSchema($cleanPerson, $this->personSchema);

        $this->assertEquals(true, $result);
    }
}