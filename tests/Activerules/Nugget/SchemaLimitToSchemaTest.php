<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

class SchemaLimitToSchemaTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // Use the Activerules dereferencer
        $dereferencer = \Activerules\JsonReference\Dereferencer::draft4();

        $validPersonArray = ['name' => 'Brian'];
        $this->validPersonJSON = json_encode($validPersonArray);

        $dirtyPersonArray = array_merge($validPersonArray, array('lorem' => array('remove' => 'me'), 'ipsum' => 'More BAD text to remove.'));
        $this->dirtyPersonJSON = json_encode($dirtyPersonArray);

        // All test will have these variables available to them under $this->
        $this->schema = new \Activerules\Nugget\Schema();
        $this->personSchema = $dereferencer->dereference('file://' . __DIR__ . '/schema/person.json');
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
        $cleanPerson = $this->schema->limitToSchema($this->dirtyPersonJSON, $this->personSchema);

        $this->assertEquals($cleanPerson, $this->validPersonJSON);

        $result = $this->schema->meetsSchema($cleanPerson, $this->personSchema);

        $this->assertEquals(true, $result);
    }
}
