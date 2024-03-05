<?php
/**
 * Import XML file data loader.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportDataLoader
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer\ImportDataLoader;

use Immerce\FaktumNavConnector\XmlTransformer\Factory\XmlNodeTransformerFactory;
use Immerce\FaktumNavConnector\XmlTransformer\XmlNodeTransformerInterface;
use Magento\Framework\Xml\Parser as XmlParser;

/**
 * Import XML file data loader class.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportDataLoader
 * @since   1.0.0
 */
class ImportXmlFileLoader implements ImportDataLoaderInterface
{
    private XmlParser $xmlParser;

    protected XmlNodeTransformerFactory $xmlNodeTransformerFactory;

    protected string $rootNode = 'item';

    public function __construct(
        XmlParser $xmlParser,
        XmlNodeTransformerFactory $xmlNodeTransformerFactory
    ) {
        $this->xmlParser = $xmlParser;
        $this->xmlNodeTransformerFactory = $xmlNodeTransformerFactory;
    }

    /**
     * @inheritDoc
     */
    public function load(string $file): array
    {
        $data = $this->xmlParser->load($file)->xmlToArray();

        $data[$this->getRootNode()] = $data[$this->getRootNode()][$this->getRootNodeSingular()] ?? $data[$this->getRootNode()];


        if (! ($data[$this->getRootNode()][0] ?? false)) {
            $tmpItem = $data[$this->getRootNode()];
            $data[$this->getRootNode()] = [
                $tmpItem,
            ];
        }

        foreach ($data[$this->getRootNode()] as $index => $item) {
            if(is_array($item)){
                foreach ($item as $field => $value) {
                    $transfomer = $this->xmlNodeTransformerFactory->create($field);

                    if (! $transfomer instanceof XmlNodeTransformerInterface) {
                        continue;
                    }

                    if(is_array($value)){
                        $data[$this->getRootNode()][$index][$field] = $transfomer->transform($value);
                    }
                }
            }

        }

        return $data[$this->getRootNode()];
    }

    public function setRootNode(string $nodeName): void
    {
        $this->rootNode = $nodeName;
    }

    /**
     * @return string
     */
    public function getRootNodeSingular(): string
    {
        return $this->rootNode;
    }

    public function getRootNode(): string
    {
        return $this->rootNode . 's';
    }
}
