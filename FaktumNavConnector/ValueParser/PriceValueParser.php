<?php
/**
 * Price value parser.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\ValueParser;

/**
 * Price value parser class.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @since   1.0.0
 */
class PriceValueParser implements ValueParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse($value): float
    {
        return floatval(preg_replace("/[^-0-9.]/", '', $value));
    }
}
