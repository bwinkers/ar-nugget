<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class SchemaBuilderMergeRequiredTest extends TestCase {
  
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
    public function testMergingRequiredProperties() 
    {
        $parent = new \StdClass();
        $parent->required = ['name','address'];
        
        $child = new \StdClass();
        $child->required = ['age', 'address', 'alias'];
        
        $this->builder->mergeRequired($parent, $child);
        
        $expected = ['address', 'age', 'alias', 'name'];
        asort($expected);
        $matches = array_intersect_key($expected, $child->required);

        $req = $child->required;

        $this->assertEquals($expected, $req);
    }

}