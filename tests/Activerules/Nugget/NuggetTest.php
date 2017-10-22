<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;





class NuggetTest extends TestCase {
  
    public function __construct($name = null, array $data = array(), $dataName = '') {
        
        $this->

        parent::__construct($name, $data, $dataName);
    }

    public function testIsValid() {
      
        $nugget = new \Activerules\Nugget\Nugget();
        
        $dereferencer  = \League\JsonReference\Dereferencer::draft4();
        $personSchema        = $dereferencer->dereference('file://' . __DIR__ . '/schema/person.json');

        $input = '{"name":"Brian"}';

        $result = $nugget->isValid($input, $personSchema);
        
        echo $result;
        
        $this->assertEquals(true, $result);
    }
    
    public function testIsValidFailure() {
      
        $nugget = new \Activerules\Nugget\Nugget();
        
        $dereferencer  = \League\JsonReference\Dereferencer::draft4();
        $personSchema        = $dereferencer->dereference('file://' . __DIR__ . '/schema/person.json');

        $input = '{"noName":"Brian"}';

        $result = $nugget->isValid($input, $personSchema);
        
        //echo $result;
        
        $this->assertEquals(true, $result);
    }

}