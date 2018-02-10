<?php

namespace Activerules\Nugget;

use Activerules\Nugget\GClient;
use Activerules\Nugget\Exceptions\NuggetException;
use Google_Service_Sheets;

/**
 * The Nugget Google Client V4
 */
class GSheet extends Google_Service_Sheets
{

    /**
     *
     * @param array $credentials
     * @param array $scopes
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * 
     * @param string $spreadsheetID
     * @param string $range
     * @return type
     */
    public function getSpreadsheetValues($spreadsheetID, $range)
    {
        try {
            return $this->spreadsheets_values->get($spreadsheetID, $range);
        } catch (Exception $ex) {
            // Log error
        }
    }
}
