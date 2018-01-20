<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;


// Need more test


class NuggetRealFileTest extends TestCase {
  
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
     * A known valid filename should pass validation
     */
    public function testDirectoryResulstAreCorrect() 
    {
        $testDir = realpath('./tests/Activerules/Nugget');
        
        // Create a directory iterator for the defined objects directory
        $files = new \DirectoryIterator($testDir);

        // Iterate through object definitions
        foreach ($files as $fileInfo) {
            $filename = $fileInfo->getFilename();

            switch($filename) {
               case '.':
                    $this->assertEquals(false,  $this->nugget->realDirFile($fileInfo));
                  break;
               
               case '..':
                    $this->assertEquals(false,  $this->nugget->realDirFile($fileInfo));
                  break;
               
               default:
                  if(! $fileInfo->isDir()){
                    $this->assertEquals(true,  $this->nugget->realDirFile($fileInfo));
                  }
                  break;
            }
        }
    }
    
}