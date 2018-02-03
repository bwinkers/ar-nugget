<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

class SchemaBuilderLoadPropertyFileTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // All test will have these variables available to them under $this->
        $this->builder = new \Activerules\Nugget\SchemaBuilder();
    }

    /**
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testLoadedPropertyFileIsValid()
    {
        $propFile = realpath(__DIR__ . '/properties/test.json');

        $result = $this->builder->loadPropertyFile($propFile);

        $this->assertEquals('property', $result->test);
    }
}