<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class SchemaBuilderMergePropsTest extends TestCase {
  
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
    public function testMergingProperties() 
    {
        $parent = new \StdClass();
        $parent->properties = ['name','address'];
        
        $child = new \StdClass();
        $child->properties = ['age', 'address', 'alias'];
        
        $this->builder->mergeProps($parent, $child);
        
        $expected = ['address', 'age', 'alias', 'name'];

        $matches = array_intersect_key($expected, $child->properties);

        $props = $child->properties;

        $this->assertEquals($expected, $props);
    }

}