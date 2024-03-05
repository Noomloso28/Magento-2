<?php
/**
 * XML node transformer interface.
 *
 * @package Immerce\FaktumNavConnector\XmlTransformer
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\XmlTransformer;

/**
 * XML node transformer interface.
 *
 * @package Immerce\FaktumNavConnector\XmlTransformer
 * @since   1.0.0
 */
interface XmlNodeTransformerInterface
{
    /**
     * @param array $nodeData
     * @return array
     */
    public function transform(array $nodeData): array;
}
