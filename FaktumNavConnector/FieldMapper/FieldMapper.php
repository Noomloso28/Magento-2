<?php
/**
 * Field mapper.
 *
 * @package Immerce\FaktumNavConnector\FieldMapper
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\FieldMapper;

use Magento\Framework\Xml\Parser as XmlParser;

/**
 * Field mapper class.
 *
 * @package Immerce\FaktumNavConnector\FieldMapper
 * @since   1.0.0
 */
class FieldMapper implements FieldMapperInterface
{
    protected array $mapping = [];

    protected string $definitionPath = '';

    protected array $mappingDefinition = [];

    /**
     * @var XmlParser
     */
    protected XmlParser $xmlParser;

    public function __construct(
        XmlParser $xmlParser
    ) {
        $this->xmlParser = $xmlParser;
    }

    /**
     * @inheritDoc
     */
    public function map(array $data, string $scope = 'base'): array
    {
        $result = [];

        foreach ($data as $field => $value) {
            if (! array_key_exists($field, $this->mappingDefinition[$scope])) {
                $result[$field] = $value;
                continue;
            }

            $newFieldKey = $this->mappingDefinition[$scope][$field]['field'];
            $result[$newFieldKey] = $value;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function loadDefinition(string $entity): array
    {
        $csvMappingXml = sprintf(
            '%s/%s-import-mapping.xml',
            $this->definitionPath,
            $entity
        );

        if (! file_exists($csvMappingXml)) {
            return [];
        }

        $this->mappingDefinition = $this->xmlParser->load($csvMappingXml)->xmlToArray();
        $this->mappingDefinition = $this->parseMappingConfig($this->mappingDefinition);


        return $this->mappingDefinition;
    }

    /**
     * @return array
     */
    public function getMappingDefinition(): array
    {
        return $this->mappingDefinition;
    }

    /**
     * @inheritDoc
     */
    public function setDefinitionPath(string $definitionPath): void
    {
        $this->definitionPath = rtrim($definitionPath, '/');
    }

    /**
     * Parses the XML CSV mapping config.
     *
     * @param array $config
     *
     * @return array
     */
    protected function parseMappingConfig(array $config): array
    {
        $parsedConfig = [];

        $result = [
            'base' => [
                'product_number' => [
                    'type' => 'text',
                    'field' => 'sku',
                ]
            ]
        ];

        $config = $config['mapping'] ?? $config;

        if (! ($config['fields'][0] ?? false)) {
            $tmpItem = $config['fields'];
            $config['fields'] = [
                $tmpItem,
            ];
        }

        foreach ($config['fields'] as $scopeMappings) {
            $scope = $scopeMappings['_attribute']['scope'] ?? 'base';

            foreach ($scopeMappings['_value']['field'] as $mapping) {
                if (! array_key_exists($scope, $parsedConfig)) {
                    $parsedConfig[$scope] = [];
                }

                $parsedConfig[$scope][$mapping['_attribute']['name']] = [
                    'type' => $mapping['_attribute']['type'] ?? 'text',
                    'field' => $mapping['_value'],
                ];
            }
        }

        return $parsedConfig;
    }
}
