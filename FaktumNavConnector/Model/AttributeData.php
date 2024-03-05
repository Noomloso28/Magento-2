<?php
/**
 * Attribute data.
 *
 * @package Immerce\FaktumNavConnector\Model
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Model;

use Magento\Framework\Filesystem\DriverInterface;

/**
 * Attribute data class.
 *
 * @package Immerce\FaktumNavConnector\Model
 * @since   1.0.0
 */
class AttributeData
{
    const PATH = '/nav-connector/resources';

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var string
     */
    protected string $definitionPath = '';

    /**
     * @var DriverInterface
     */
    private DriverInterface $driver;

    public function __construct(
        DriverInterface $driver
    ) {
        $this->driver = $driver;
    }

    public function load(string $entity): self
    {
        $jsonMappingUrl = sprintf(
            '%s/%s-attribute-data.json',
            $this->definitionPath,
            $entity
        );

        if (!file_exists($jsonMappingUrl)) {
            return $this;
        }

        $data = $this->driver->fileGetContents($jsonMappingUrl);
        $this->data = \Safe\json_decode($data, true);

        return $this;
    }

    public function getAttributeType(string $code): string
    {
        return $this->data[$code]['type'] ?? 'text';
    }

    public function setDefinitionPath(string $definitionPath): void
    {
        $this->definitionPath = rtrim($definitionPath, '/') . self::PATH;
    }

    public function selectAttributeOptionExists(string $code, $value): bool
    {
        return ! empty(
            array_filter($this->data[$code]['options'], function (array $optin) use ($value) {
                return $optin['value'] === $value;
            })
        );
    }
}
