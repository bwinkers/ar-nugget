<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetConvertSchemaFileTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
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
     * A trailing slash path has a single trailing slash at the end
     */
    public function testURLRefsWork() 
    {
        $filePath = realpath('./tests/Activerules/Nugget/schema/Test.json');
     
        $path = $this->nugget->convertSchemaFile($filePath, 'https://schema.izzup.com/', '#/components/schema/');

        $this->assertEquals('{
    "title": "Test",
    "description": "For testing only",
    "properties": {
        "testProp": {
            "description": "TestProperty.",
            "$ref": "https://schema.izzup.com/TestProp"
        }
    },
    "type": "object"
}', $path);
    }

}