<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

// Need more test


class NuggetWriteFileTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        $this->nugget = new \Activerules\Nugget\Filesys();
    }

    /**
     * A trailing slash path has a single trailing slash at the end
     */
    public function testWrittenFileHasCorrectContent()
    {
        $content = "I hope I'm found.";

        $filePath = './tests/Activerules/Nugget/schema/written.json';

        $this->nugget->writeFile($content, $filePath);

        $writtenContent = file_get_contents(realpath($filePath));

        $this->assertEquals($content, $writtenContent);
    }
}