<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

// Need more test


class SchemaBuilderConvertSchemaFileTest extends TestCase
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
    public function testURLRefsWork()
    {
        $filePath = realpath('./tests/Activerules/Nugget/schema/Test.json');

        $path = $this->builder->convertSchemaFile($filePath, 'https://schema.izzup.com/', '#/components/schema/');

        $this->assertEquals(json_decode('{
    "title": "Test",
    "description": "For testing only",
    "properties": {
        "testProp": {
            "description": "TestProperty.",
            "$ref": "https://schema.izzup.com/TestProp"
        }
    },
    "type": "object"
}'), json_decode($path));
    }
}