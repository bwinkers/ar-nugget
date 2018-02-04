<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class GSheetTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // Mock credentials
        $credentials = [
            'private_key_id' => 'key123',
            'private_key' => 'privatekey',
            'client_email' => 'test@example.com',
            'client_id' => 'client123',
            'type' => 'service_account',
        ];
        
        $readScopes = \Google_Service_Sheets::SPREADSHEETS_READONLY;

        // All test will have these variables available to them under $this->
        $this->readClient = new \Activerules\Nugget\GClient($credentials);
        $this->readClient->setScopes($readScopes);
        
        $this->errors = array();
        set_error_handler(array($this, "errorHandler"));
    }
    
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
        $this->errors[] = compact("errno", "errstr", "errfile",
            "errline", "errcontext");
    }

    /**
     * The client class type should be correct
     */
    public function testService()
    {
        $sheet = new \Activerules\Nugget\GSheet($this->readClient);
        
        $classType = get_class($sheet);

        $this->assertEquals(@'Activerules\Nugget\GSheet', $classType);
    }
    
    /**
     * Ensure invalid data fails.
     * Testing with valid data only happens in dev.
     */
    public function testBogusRequestFails() {
        
            $this->expectException('Exception');

            $gSheet = new \Activerules\Nugget\GSheet($this->readClient);
        
            $spreadsheetID = 'sizzle-frix-find-me-not';

            $range = 'IncomingProperties!A1:C';

            $response = $gSheet->getSpreadsheetValues($spreadsheetID, $range);

            $properties = $response->getValues();
    }
}
