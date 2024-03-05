<?php
/**
 * Price value parser factory.
 *
 * @package Immerce\FaktumNavConnector\ValueParser\Factory
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\ValueParser\Factory;

use Immerce\FaktumNavConnector\ValueParser\PriceValueParser;
use Immerce\FaktumNavConnector\ValueParser\ValueNullParser;
use Immerce\FaktumNavConnector\ValueParser\ValueParserInterface;
use Immerce\FaktumNavConnector\ValueParser\YesNoValueParser;

/**
 * Price value parser factory class.
 *
 * @package Immerce\FaktumNavConnector\ValueParser\Factory
 * @since   1.0.0
 */
class ValueParserFactory
{
    public function create(string $type): ValueParserInterface
    {
        switch ($type) {
//            case 'select':
//                //return new AbcValueParser();
//                break;
//            case 'text':
//                break;
            case 'boolean':
                return new YesNoValueParser();
            case 'price':
                return new PriceValueParser();
//            case 'date':
//                break;
//            case 'textarea':
//                break;
//            case 'gallery':
//                break;
//            case 'media_image':
//                break;
//            case 'weight':
//                break;
            default:
                return new ValueNullParser();
        }
    }
}
