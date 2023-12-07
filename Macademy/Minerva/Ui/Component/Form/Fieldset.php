<?php
namespace Macademy\Minerva\Ui\Component\Form;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\FieldFactory;
class Fieldset extends \Magento\Ui\Component\Form\Fieldset
{
    protected $fieldFactory;
    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = [],
        FieldFactory $fieldFactory)
    {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
    }
    public function getChildComponents()
    {
        $options = [
            0 => [
                'label' => 'Option 1',
                'value' => 1
            ],
            1  => [
                'label' => 'Option 2',
                'value' => 2
            ],
            2 => [
                'label' => 'Option 3',
                'value' => 3
            ],
        ];
        $fields = [
            [
                'label' => __('Label'),
                'value' => __('Label Value'),
                'formElement' => 'input',
            ],
            [
                'label' => __('Checkbox'),
                'value' => __('0'),
                'formElement' => 'checkbox',
            ],
            [
                'label' => __('Dropdown'),
                'options' => $options,
                'formElement' => 'select',
            ],
        ];
        foreach ($fields as $key => $field) {
            $fieldInstance = $this->fieldFactory->create();
            $name = 'custom_field_' . $key;
            $fieldInstance->setData(
                [
                    'config' => $field,
                    'name' => $name
                ]
            );
            $fieldInstance->prepare();
            $this->addComponent($name, $fieldInstance);
        }
        return parent::getChildComponents();
    }
}
