<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class GSheetReaderTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        $this->sheetUrl = 'https://docs.google.com/spreadsheets/d/1NeU79bJ-Zic-fwKK2PPuxntNXnbkyMKf6ZTIUHp1n4s/export?format=csv&id=1NeU79bJ-Zic-fwKK2PPuxntNXnbkyMKf6ZTIUHp1n4s&gid=0';
    }

    /**
     * The gClient class type should be correct
     */
    public function testGClientReader()
    {
        $sheet = new \Activerules\Nugget\GSheetReader($this->sheetUrl);

        $this->assertEquals(true, is_object($sheet));
    }
}
