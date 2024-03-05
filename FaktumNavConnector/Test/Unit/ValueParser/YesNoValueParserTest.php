<?php
/**
 * Options value parser test.
 *
 * @package Immerce\FaktumNavConnector\Test\Unit\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Test\Unit\ValueParser;

use Immerce\FaktumNavConnector\ValueParser\YesNoValueParser;
use PHPUnit\Framework\TestCase;

class YesNoValueParserTest extends TestCase
{
    private YesNoValueParser $yesNoValueParser;

    protected function setUp(): void
    {
        $this->yesNoValueParser = new YesNoValueParser();
    }
    public function testParsedValueIsValidTrue(): void
    {
        $result = $this->yesNoValueParser->parse(true);

        $this->assertEquals(1, $result);
        $this->assertIsInt($result);
    }
    public function testParseReturnsFalseIfInvalidValueGiven(): void
    {
        $result = $this->yesNoValueParser->parse(false);

        $this->assertEquals(0, $result);
        $this->assertIsInt($result);
    }

}
