<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetMergePropsTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        // All test will have these variables available to them under $this->
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
     * A known valid schema, fetched remotely, should pass validation
     */
    public function testMergingProperties() 
    {
        $parent = new \StdClass();
        $parent->properties = ['name','address'];
        
        $child = new \StdClass();
        $child->properties = ['age', 'address', 'alias'];
        
        $this->nugget->mergeProps($parent, $child);
        
        $expected = ['address', 'age', 'alias', 'name'];

        $matches = array_intersect_key($expected, $child->properties);

        $props = $child->properties;

        $this->assertEquals($expected, $props);
    }

}