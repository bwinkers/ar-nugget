<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget file system functions
 */
class Filesys
{
    /**
     * Write a file out
     * 
     * @param string $data
     * @param string $path
     */
    public function writeFile($data, $path)
    {
        $filePath = fopen($path, 'w');

        // Write the spec to the file pointer
        fwrite($filePath, $data);

        // Close the file pointer
        fclose($filePath);
    }

    /**
     * Making sure the trailing slash in path is consistent.
     * 
     * @param string $path
     */
    public function cleanPath($path)
    {
        return rtrim($path, '/') . '/';
    }

    /**
     * Filter out dot files and directories
     * 
     * @param string $fileInfo
     * @return boolean
     */
    public function realDirFile($fileInfo)
    {
        $name = $fileInfo->getFilename();

        $isValid = false;

        if (!$fileInfo->isDot() && !$fileInfo->isDir() && substr($name, 0, 1) != '.') {
            $isValid = true;
        }

        return $isValid;
    }

}
