<?php
/**
 * Null value parser test.
 *
 * @package Immerce\FaktumNavConnector\Test\Unit\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Test\Unit\ValueParser;

use Immerce\FaktumNavConnector\ValueParser\ValueNullParser;
use PHPUnit\Framework\TestCase;

class ValueNullParserTest extends TestCase
{
    private ValueNullParser $valueNullParser;

    protected function setUp(): void
    {
        $this->valueNullParser = new ValueNullParser();
    }

    public function testParsedValueDoesNotChange()
    {
        $result = $this->valueNullParser->parse(true);

        $this->assertEquals(true, $result);
        $this->assertIsBool($result);

        $result = $this->valueNullParser->parse(1);

        $this->assertEquals(1, $result);
        $this->assertIsInt($result);
    }
}
