<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class NuggetTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // All test will have these variables available to them under $this->
        $this->nugget = new \Activerules\Nugget\Nugget();
    }

    /**
     * A known valid schema should pass validation
     */
    public function testFilesysType()
    {
        $result = is_a($this->nugget->filesys, 'Activerules\Nugget\Filesys');
        $this->assertEquals(true, $result);
    }

    /**
     * A known invalid schema should fail validation
     */
    public function testSchemaType()
    {
        $result = is_a($this->nugget->schema, 'Activerules\Nugget\Schema');
        $this->assertEquals(true, $result);
    }

    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testSchemaBuilderType()
    {
        $result = is_a($this->nugget->builder, 'Activerules\Nugget\SchemaBuilder');
        $this->assertEquals(true, $result);
    }

    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testOpenAPIType()
    {
        $result = is_a($this->nugget->openAPI, 'Activerules\Nugget\openAPI');
        $this->assertEquals(true, $result);
    }
}
