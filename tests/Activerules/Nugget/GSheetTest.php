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
        $config = '/home/brian/.google/izzup-client_secret.json';

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
    public function testService()
    {
        $sheet = new \Activerules\Nugget\GSheet($this->readClient);
        
        $classType = get_class($sheet);

        $this->assertEquals('Activerules\Nugget\GSheet', $classType);
    }
}
