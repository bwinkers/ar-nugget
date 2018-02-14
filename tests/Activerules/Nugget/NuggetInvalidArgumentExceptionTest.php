<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\Exception\NuggetInvalidArgumentException;

// Need more test


class NuggetInvalidArgumentTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        $this->openAPI = new \Activerules\Nugget\OpenAPI();
    }

    /**
     * The exception should be of type exception
     */
    public function testInvalidArgumentIsAInvalidArgument()
    {
        $this->expectException('\Activerules\Nugget\Exception\NuggetInvalidArgumentException');

        throw new \Activerules\Nugget\Exception\NuggetInvalidArgumentException('What What!');
    }
}
