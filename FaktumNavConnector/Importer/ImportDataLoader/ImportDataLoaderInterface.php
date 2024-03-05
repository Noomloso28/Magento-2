<?php
/**
 * Import data loader interface.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportDataLoader
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer\ImportDataLoader;

/**
 * Import data loader interface.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportDataLoader
 * @since   1.0.0
 */
interface ImportDataLoaderInterface
{
    /**
     * @param string $file
     *
     * @return array
     */
    public function load(string $file): array;
}
