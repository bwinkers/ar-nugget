<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

// Need more test


class SchemaBuilderConvertSchemaFileRefsTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        $this->builder = new \Activerules\Nugget\SchemaBuilder();
    }

    /**
     * A trailing slash path has a single trailing slash at the end
     */
    public function testConvertingRefsWorks()
    {
        // Define input and outpur schema directories
        $schemaDir = realpath('./tests/Activerules/Nugget/schema/');
        $schemaOut = realpath('./tests/Activerules/Nugget/schemaOut/');

        // Run the nugget to copnvert the schema $refs
        $this->builder->convertSchemaFileRefs($schemaDir, $schemaOut, 'https://schema.izzup.com/', '#/components/schema/');

        // Read the created file contents
        $file = realpath('./tests/Activerules/Nugget/schemaOut/Test.json');
        $json = file_get_contents($file);

        $this->assertEquals(preg_replace('/\v(?:[\v\h]+)/', '', '{
    "title": "Test",
    "description": "For testing only",
    "properties": {
        "testProp": {
            "description": "TestProperty.",
            "$ref": "https://schema.izzup.com/TestProp"
        }
    },
    "type": "object"
}'), preg_replace('/\v(?:[\v\h]+)/', '', $json));
    }
}
