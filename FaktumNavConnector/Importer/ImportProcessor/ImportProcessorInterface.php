<?php
/**
 * Import processor interface.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportProcessor
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer\ImportProcessor;

/**
 * Import processor interface.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportProcessor
 * @since   1.0.0
 */
interface ImportProcessorInterface
{
    /**
     * @return bool
     */
    public function process(string $impoortFile): bool;

    /**
     * @return string
     */
    public function getEntity(): string;

    /**
     * Retrieves attribute path from the current store.
     * @return string
     */
    public function configDataPath(): string;
}
