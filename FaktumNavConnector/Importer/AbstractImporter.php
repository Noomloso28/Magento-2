<?php
/**
 * Abstract importer.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer;

use Immerce\FaktumNavConnector\FieldMapper\FieldMapperInterface;
use Immerce\FaktumNavConnector\Model\AttributeData;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir\Reader as ModuleDirReader;

/**
 * Abstract importer class.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @since   1.0.0
 */
abstract class AbstractImporter
{
    protected FieldMapperInterface $fieldMapper;

    /**
     * @var ModuleDirReader
     */
    protected ModuleDirReader $moduleDirReader;

    protected array $data = [];

    /**
     * @var array|null
     */
    protected ?array $currentItem = [];


    /**
     * @var AttributeData
     */
    protected AttributeData $attributeData;

    /**
     * @var DirectoryList
     */
    private DirectoryList $directoryList;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    public function __construct(
        FieldMapperInterface $fieldMapper,
        ModuleDirReader $moduleDirReader,
        AttributeData $attributeData,
        DirectoryList $directoryList,
        Filesystem $filesystem
    ) {
        $this->moduleDirReader = $moduleDirReader;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->attributeData = $attributeData;
        $this->attributeData->setDefinitionPath(
            $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath()
        );
        $this->fieldMapper = $fieldMapper;
        $this->fieldMapper->setDefinitionPath(
            $this->moduleDirReader->getModuleDir('etc', 'Immerce_FaktumNavConnector').'/config'
        );
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    abstract public function import(array $data): bool;

    protected function mapFields(array $data): array
    {
        return $this->fieldMapper->map($data);
    }

    protected function nextItem(): ?array
    {
        $this->currentItem = array_shift($this->data);
        return $this->currentItem;
    }

    protected function getCurrentItem(): array
    {
        return $this->currentItem;
    }
}
