<?php
/**
 * Value parser interface.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\ValueParser;

/**
 * Value parser interface.
 *
 * @package Immerce\FaktumNavConnector\ValueParser
 * @since   1.0.0
 */
interface ValueParserInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function parse($value);
}
