<?php
/**
 * Price value parser test.
 *
 * @package Immerce\FaktumNavConnector\Test\Unit\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Test\Unit\ValueParser;

use Immerce\FaktumNavConnector\ValueParser\PriceValueParser;
use PHPUnit\Framework\TestCase;

/**
 * Price value parser test class.
 *
 * @package Immerce\FaktumNavConnector\Test\Unit\ValueParser
 * @since   1.0.0
 */
class PriceValueParserTest extends TestCase
{
    private PriceValueParser $priceValueParser;

    protected function setUp(): void
    {
        $this->priceValueParser = new PriceValueParser();
    }

    public function testParsedValueIsValidPrice(): void
    {
        $result = $this->priceValueParser->parse('9.95');

        $this->assertEquals(9.95, $result);
        $this->assertIsFloat($result);
    }

    public function testParseReturnsZeroIfInvalidValueGiven(): void
    {
        $result = $this->priceValueParser->parse('foo');

        $this->assertEquals(0, $result);
        $this->assertIsFloat($result);
    }
}
