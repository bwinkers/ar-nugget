<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetPathRootTest extends TestCase {
  
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
    public function testRootWithTrailingSlashWorks() 
    {
        $path = $this->nugget->pathRoot('/my/path/');

        $this->assertEquals('/my/path/', $path);
    }
    
    /**
     * A trailing slash path has a single trailing slash at the end
     */
    public function testRootWithoutTrailingSlashWorks() 
    {
        $path = $this->nugget->pathRoot('/my/path');

        $this->assertEquals('/my/path/', $path);
    }
}