<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

// Need more test


class OpenAPIJSONTypeTest extends TestCase
{
    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        // Use the Activerules dereferencer
        $dereferencer = \Activerules\JsonReference\Dereferencer::draft4();

        $this->openAPI = new \Activerules\Nugget\OpenAPI();
    }

    /**
     * An object missing required fields should fail.
     */
    public function testInvalidTypeFails()
    {
        $result = $this->openAPI->jsonType('fooglesnaps');

        $this->assertEquals(false, $result);
    }

    /**
     * An object with valid core data should pass nugget validation
     */
    public function testValidTypePasses()
    {
        $result = $this->openAPI->jsonType('Text');

        $this->assertEquals('string', $result);
    }

}
