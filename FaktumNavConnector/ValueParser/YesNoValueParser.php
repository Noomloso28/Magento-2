<?php
/**
 * Yes/no value parser.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\ValueParser;

/**
 * Yes/no value parser class.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @since   1.0.0
 */
class YesNoValueParser implements ValueParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse($value): int
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ? 1 : 0;
    }
}
