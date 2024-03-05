<?php
/**
 * Field mapper interface.
 *
 * @package Immerce\FaktumNavConnector\FieldMapper
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\FieldMapper;

/**
 * Field mapper interface.
 *
 * @package Immerce\FaktumNavConnector\FieldMapper
 * @since   1.0.0
 */
interface FieldMapperInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function map(array $data): array;

    /**
     * @param string $definitionPath
     *
     * @return void
     */
    public function setDefinitionPath(string $definitionPath): void;

    /**
     * @param string $entity
     *
     * @return array
     */
    public function loadDefinition(string $entity);

    /**
     * @return array
     */
    public function getMappingDefinition(): array;
}
