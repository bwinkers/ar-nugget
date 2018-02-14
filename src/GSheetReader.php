<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exception\NuggetException;

/**
 * The Nugget anonymous Google Spreadsheet reader.
 * This reads data from a public spreadsheet.
 */
class GSheetReader
{
    public $gSheet;

    /**
     *
     * @param array $credentials
     * @param array $scopes
     */
    public function __construct($url)
    {
        $gSheet = $this->loadGsheetCSV($url);
        $this->gSheet = $gSheet;
    }

    /**
     * Fetch CSV data from public Google Spreadsheet
     * @param type $url
     * @return object
     */
    public function loadGsheetCSV($url)
    {
        $file = file_get_contents($url);
        return $file;
    }

    /**
     *
     * @return object
     */
    public function gSheet()
    {
        return $this->gSheet;
    }
}
