<?php
/**
 * Product Attributes generate.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @version 1.0.0
 * @license GPL 2.0+
 */
declare(strict_types=1);
namespace Immerce\FaktumNavConnector\Model;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class AttributesToJsonProcessor
{
    CONST PATH = 'nav-connector/resources/product-attribute-data.json';

    protected EavConfig $eavConfig;
    private DirectoryList $directoryList;
    protected Filesystem $filesystem;

    public function __construct(
        EavConfig $eavConfig,
        DirectoryList $directoryList,
        Filesystem $filesystem
    )
    {
        $this->eavConfig = $eavConfig;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
    }

    public function processing()
    {
        $attributesArray = $this->getAttributesType();
        //Create JSON file
        $this->createJsonFile($attributesArray);
    }

    private function createJsonFile( array $attributesArray)
    {
        $directoryWriter = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $directoryWriter->writeFile(static::PATH,\Safe\json_encode($attributesArray));
    }

    protected function getAttributesType(): array
    {
        $attributesArray = $this->eavConfig->getEntityAttributes(
            Product::ENTITY,
            Product::class
        );

        $result = [];
        $i = 0;
        $count = count($attributesArray);
        foreach (array_keys($attributesArray) as $code) {
            $attribute = $this->eavConfig->getAttribute('catalog_product', $code);
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

    /**
     * Outputs the progress in the console.
     *
     * @param  int $done
     * @param  int $total
     * @param  string $info
     * @param  int $width
     * @return string
     */
    protected function progressBar(int $done, int $total, string $info = '', $width = 50)
    {
        $perc = round(($done * 100) / $total);
        $bar  = (int) round(($width * $perc) / 100);
        return sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat('=', $bar), str_repeat(' ', $width-$bar), $info);
    }

}
