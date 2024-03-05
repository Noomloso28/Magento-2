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

use Magento\Customer\Model\Customer;

class CustomerAttributesToJsonProcessor extends AttributesToJsonProcessor
{
    CONST PATH = 'nav-connector/resources/customer-attribute-data.json';

    protected function getAttributesType(): array
    {
        $attributesArray = $this->eavConfig->getEntityAttributes(
            Customer::ENTITY,
            Customer::class
        );

        $result = [];
        $i = 0;
        $count = count($attributesArray);
        foreach (array_keys($attributesArray) as $code) {
            $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, $code);
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
