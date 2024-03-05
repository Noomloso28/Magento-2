<?php
/**
 * XML node transformer factory.
 *
 * @package Immerce\FaktumNavConnector\XmlTransformer
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\XmlTransformer\Factory;

use Immerce\FaktumNavConnector\XmlTransformer\XmlNodeTransformerInterface;

/**
 * XML node transformer factory class.
 *
 * @package Immerce\FaktumNavConnector\XmlTransformer
 * @since   1.0.0
 */
class XmlNodeTransformerFactory
{
    public const TRANSFORMALE_NODES = [
        'images',
    ];

    public function create(string $nodeName): ?XmlNodeTransformerInterface
    {
        if (! in_array($nodeName, self::TRANSFORMALE_NODES)) {
            return null;
        }

        $transformerClass = \Safe\sprintf(
            'Immerce\FaktumNavConnector\XmlTransformer\%sNodeTransformer',
            ucfirst($nodeName)
        );

        if (! class_exists($transformerClass)) {
            return null;
        }

        return new $transformerClass();
    }
}
