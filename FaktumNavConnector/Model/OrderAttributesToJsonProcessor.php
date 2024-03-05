<?php
/**
 * Customer Attributes generate.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @version 1.0.0
 * @license GPL 2.0+
 */
declare(strict_types=1);
namespace Immerce\FaktumNavConnector\Model;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class OrderAttributesToJsonProcessor extends AttributesToJsonProcessor
{
    CONST PATH = 'nav-connector/resources/order-attribute-data.json';

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EavConfig $eavConfig
     * @param DirectoryList $directoryList
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     */
    public function __construct(
        EavConfig $eavConfig,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        parent::__construct($eavConfig, $directoryList, $filesystem);
    }

    protected function getAttributesType(): array
    {
        $attributesArray = $this->eavConfig->getEntityAttributes(
            Order::ENTITY,
            Order::class
        );

        $result = [];
        $i = 0;
        $count = count($attributesArray);

        foreach (array_keys($attributesArray) as $code) {
            $attribute = $this->eavConfig->getAttribute(Order::ENTITY, $code);
            $options = $attribute->getOptions();
            $optionData = [];
            foreach ($options as $option) {
                $optionData[] = array(
                    'label' => $option->getLabel(),
                    'value' => $option->getValue()
                );
            }

            $result[$attribute->getAttributeCode()] = array(
                'attribute_id' => $attribute->getAttributeId(),
                'label' => $attribute->getStoreLabel(),
                'code' => $attribute->getAttributeCode(),
                'type_id' => $attribute->getEntityTypeId(),
                'type' => $attribute->getFrontendInput(),
                'default' => $attribute->getDefaultValue(),
                'options' => $optionData
            );

            echo $this->progressBar($i, $count) ;

            $i++;
        }

        return $result;
    }

}
