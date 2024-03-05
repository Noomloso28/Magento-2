<?php
/**
 * Value null parser.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\ValueParser;

/**
 * Value null parser class.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @since   1.0.0
 */
class ValueNullParser implements ValueParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse($value)
    {
        return $value;
    }
}
