<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\exceptions\NuggetException;

// Need more test


class NuggetExceptionTest extends TestCase
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
    public function testExceptionIsAnException()
    {
        $this->expectException('\Activerules\Nugget\exceptions\NuggetException');

        throw new \Activerules\Nugget\exceptions\NuggetException('What What!');
    }

    /**
     * The exception should be of type exception
     */
    public function testNuggetExceptionIsAnException()
    {
        $this->expectException('\Activerules\Nugget\exceptions\NuggetException');

        $this->openAPI->jsonType([]);
    }

}
