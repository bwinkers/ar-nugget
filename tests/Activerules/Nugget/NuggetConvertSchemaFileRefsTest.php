<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetConvertSchemaFileRefsTest extends TestCase {
  
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
    public function testConvertingRefsWorks() 
    {
      // Define input and outpur schema directories
      $schemaDir = realpath('./tests/Activerules/Nugget/schema/');
      $schemaOut = realpath('./tests/Activerules/Nugget/schemaOut/');
      
      // Run the nugget to copnvert the schema $refs
      $this->nugget->convertSchemaFileRefs($schemaDir, $schemaOut, 'https://schema.izzup.com/', '#/components/schema/');
      
      // Read the created file contents
      $file = realpath('./tests/Activerules/Nugget/schemaOut/Test.json');
      $JSON = file_get_contents($file);
      
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
}', $JSON);
      
    
    }

}