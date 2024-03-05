<?php
/**
 * Product importer.
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
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\StoreManagerInterface;

class OrderImporter extends AbstractImporter
{
    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    public function __construct(
        FieldMapperInterface $fieldMapper,
        ModuleDirReader $moduleDirReader,
        AttributeData $attributeData,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        OrderFactory $orderFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->orderFactory = $orderFactory;
        $this->storeManager = $storeManager;

        parent::__construct($fieldMapper, $moduleDirReader, $attributeData, $directoryList, $filesystem);
    }

    public function import(array $data): bool
    {
        $this->data = $data;
        $i = 1;
        $count = count($data);

        while ($this->nextItem()) {

            if(!array_key_exists('increment_id', $this->currentItem)){
                continue;
            }

            $incrementId = sprintf("%09d", $this->currentItem['increment_id']);
            /** check exit user */
            if((int) $incrementId > 0){
                $storeId = $this->storeManager->getStore()->getId();
                $order = $this->orderFactory->create()->setStoreId($storeId)->loadByIncrementId($incrementId);
                if($order instanceof Order && $order->hasData('entity_id')){
                    /* set new status order data */
                    if ($this->isValid($this->currentItem,'status', 'string')) {
                        //$status = strtolower($this->currentItem['status']); // some status is STATE_OPEN
                        $order->setStatus($this->currentItem['status']);
                    }
                    /** save order */
                    $order->save();
                }
            }
            echo $this->progressBar($i, $count) ;
            $i++;
        }
        return true;
    }

    /**
     * @param array $array
     * @param string $key
     * @param $type
     * @return bool
     */
    public function isValid(array $array, string $key, $type=null)
    {
        $status = false;
        if (array_key_exists($key, $array)) {
            $status = true;
        }
        /** check type validate */
        if(isset($type) && $status == true){
            switch ( $type ) {
                case 'array':
                    $status = (is_array($array[$key])) ? true : false;
                    break;
                case 'string':
                    $status = (isset($array[$key])) ? true : false;
                    break;
                case 'number':
                    $status = (is_numeric($array[$key])) ? true : false;
                    break;
                case 'bool':
                    $status = (is_bool($array[$key])) ? true : false;
                    break;
                default:
                    $status = false;
                    break;
            }
        }

        return $status;
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
