<?php

namespace Activerules\Nugget;

use Activerules\Nugget\GClient;
use Activerules\Nugget\Exceptions\NuggetException;
use Google_Service_Sheets;

/**
 * The Nugget Google Client V4
 */
class GSheet
{
    public $gSheet;

    /**
     *
     * @param array $credentials
     * @param array $scopes
     */
    public function __construct($client)
    {

        $gClient = $client->gClient();

        $sheet = new Google_Service_Sheets($gClient);

        $this->gSheet = $sheet;
    }

    /**
     *
     * @return object
     */
    public function gSheet()
    {
        return $this->gSheet;
    }

    public function getSpreadsheetValues($spreadsheetID, $range)
    {
        try {
            $vals = $this->gSheet->spreadsheets_values->get($spreadsheetID, $range);
            return $vals;
        } catch (Exception $ex) {
            // Log error
        }   
    }
}
