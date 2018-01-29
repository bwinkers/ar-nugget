<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetMergeRequiredTest extends TestCase {
  
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
    public function testMergingRequiredProperties() 
    {
        $parent = new \StdClass();
        $parent->required = ['name','address'];
        
        $child = new \StdClass();
        $child->required = ['age', 'address', 'alias'];
        
        $this->nugget->mergeRequired($parent, $child);
        
        $expected = ['address', 'age', 'alias', 'name'];
        asort($expected);
        $matches = array_intersect_key($expected, $child->required);

        $req = $child->required;

        $this->assertEquals($expected, $req);
    }

}