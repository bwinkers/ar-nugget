<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

// Need more test


class NuggetTestDev extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // Use the Activerules dereferencer
        $dereferencer = \Activerules\JsonReference\Dereferencer::draft4();

        $validPersonArray = ['name' => 'Brian'];
        $this->validPerson = json_encode($validPersonArray);

        $validPersonAddressArray = array_merge($validPersonArray, array('address' => array('type' => 'street')));
        $this->validPersonAddress = json_encode($validPersonAddressArray);

        $invalidPersonArray = ['noName' => 'Brian'];
        $this->invalidPerson = json_encode($invalidPersonArray);

        $invalidPersonAddressEnumArray = array_merge($validPersonArray, array('address' => array('type' => 'nostreet')));
        $this->invalidPersonAddressEnum = json_encode($invalidPersonAddressEnumArray);

        $invalidPersonAddressArray = array_merge($validPersonArray, array('address' => array('notype' => 'object-now-has-no-type-so-does-not-matter')));
        $this->invalidPersonAddress = json_encode($invalidPersonAddressArray);

        // All test will have these variables available to them under $this->
        $this->nugget = new \Activerules\Nugget\Nugget();
        $this->localPersonSchema = $dereferencer->dereference('file://' . dirname(__DIR__) . '/Nugget/schema/person.json');
        $this->localRefPersonSchema = $dereferencer->dereference('file://' . dirname(__DIR__) . '/Nugget/schema/refPerson.json');
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

    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testValidDataPassesRemoteSchema()
    {
        $result = $this->nugget->meetsSchema($this->validPerson, $this->remotePersonSchema);

        $this->assertEquals(true, $result);
    }

    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testInvalidDataFailsRemoteSchema()
    {
        $result = $this->nugget->meetsSchema($this->invalidPerson, $this->remotePersonSchema);

        $this->assertEquals(false, $result);
    }

    /**
     * A valid object should pass a referenced remote schema validation
     */
    public function testValidDataPassesReferencedSchema()
    {
        $result = $this->nugget->meetsSchema($this->validPersonAddress, $this->localPersonSchema);

        $this->assertEquals(true, $result);
    }
}