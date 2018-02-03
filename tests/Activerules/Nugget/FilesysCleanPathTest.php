<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class FilesysCleanPathTest extends TestCase {
  
    /**
     * This gets called before all test functions
     */
    public function setUp() 
    {
        $this->filesys = new \Activerules\Nugget\Filesys();
   }
    
    /**
     * A trailing slash path has a single trailing slash at the end
     */
    public function testRootWithTrailingSlashWorks() 
    {
        $path = $this->filesys->cleanPath('/my/path/');

        $this->assertEquals('/my/path/', $path);
    }
    
    /**
     * A trailing slash path has a single trailing slash at the end
     */
    public function testRootWithoutTrailingSlashWorks() 
    {
        $path = $this->filesys->cleanPath('/my/path');

        $this->assertEquals('/my/path/', $path);
    }
}