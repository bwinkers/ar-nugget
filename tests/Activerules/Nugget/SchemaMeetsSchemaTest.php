<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

// Need more test


class SchemaMeetsSchemaTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // Use the League dereferencer
        $dereferencer = \Activerules\JsonReference\Dereferencer::draft4();

        // A known valid Person object, it has a name property.
        $this->validPerson = '{"name":"Brian"}';

        // A known invalid Person object, it has NO name property.
        $this->invalidPerson = '{"noName":"Brian"}';

        // All test will have these variables available to them under $this->
        $this->schema = new \Activerules\Nugget\Schema();
        $this->localPersonSchema = $dereferencer->dereference('file://' . __DIR__ . '/schema/person.json');

        $this->nuggetSchema = $dereferencer->dereference('file://./Nugget.json');

        $this->missingCoreType = file_get_contents(__DIR__ . '/objects/missingCoreType-Nugget.json');

        $this->validCoreType = file_get_contents(__DIR__ . '/objects/validCoreType-Nugget.json');

        $this->invalidCoreType = file_get_contents(__DIR__ . '/objects/invalidCoreType-Nugget.json');


        // NOTE: NO REMOTE SCHEMA TESTS IN THIS FILE!
        // This test gets run by Travis CI and for some as of yet unresolved reason it does not work with remote schema.
        //$this->remotePersonSchema = $dereferencer->dereference('https://rawgit.com/bwinkers/nugget/master/tests/Activerules/Nugget/objects/person.json');
    }

    /**
     * A known valid schema should pass validation
     */
    public function testValidDataPasses()
    {
        $result = $this->schema->meetsSchema($this->validPerson, $this->localPersonSchema);

        $this->assertEquals(true, $result);
    }

    /**
     * A known invalid schema should fail validation
     */
    public function testInvalidDataFails()
    {
        $result = $this->schema->meetsSchema($this->invalidPerson, $this->localPersonSchema);

        $this->assertEquals(false, $result);
    }

    /**
     * An object missing required fields should fail.
     */
    public function testMissingCoreDataFails()
    {
        $result = $this->schema->meetsSchema($this->missingCoreType, $this->nuggetSchema);

        $this->assertEquals(false, $result);
    }

    /**
     * An object with valid core data should pass nugget validation
     */
    public function testValidRequiredCoreDataPasses()
    {
        $result = $this->schema->meetsSchema($this->validCoreType, $this->nuggetSchema);

        $this->assertEquals(true, $result);
    }

    /**
     * An object with invalid core data should fail nugget validation
     */
    public function testInvalidRequiredCoreDataFails()
    {
        $result = $this->schema->meetsSchema($this->invalidCoreType, $this->nuggetSchema);

        $this->assertEquals(false, $result);
    }
}
