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
        $config = '~/.google/izzup-client_secret.json';

        if (file_exists($config)) {

            $json = file_get_contents($config);
            if (!$credentials = json_decode($json, true)) {
                throw new LogicException('invalid json for auth config');
            }
        }
        
        $readScopes = \Google_Service_Sheets::SPREADSHEETS_READONLY;

        // All test will have these variables available to them under $this->
        $this->readClient = new \Activerules\Nugget\GClient($credentials);
        $this->readClient->setScopes($readScopes);
    }
    
    /**
     * The client class type should be correct
     */
    public function testClientType()
    {
        $clientType = get_class($this->client);
       
        $this->assertEquals('Activerules\Nugget\GClient', $clientType);
    }

    /**
     * The client class type should be correct
     */
    public function testSpreadsheet()
    {
        $gSheet = new \Activerules\Nugget\GSheet($this->readClient);
        
        $spreadsheetID = '1NeU79bJ-Zic-fwKK2PPuxntNXnbkyMKf6ZTIUHp1n4s';
        
        $range = 'IncomingProperties!A1:C';
        
        $response = $gSheet->getSpreadsheetValues($spreadsheetID, $range);
        
        $properties = $response->getValues();
        
        $this->assertEquals(true, !empty($properties));
    }
}
