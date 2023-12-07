<?php declare(strict_types=1);

namespace Boxes\SqlCommand\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class DbRelation
{

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;


    public function __construct(
        ResourceConnection $resourceConnection,
        CollectionFactory $collectionFactory
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return ResourceConnection
     */
    public function getResourceConnection(): ResourceConnection
    {
        return $this->resourceConnection;
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface|void
     */
    public function getConnection()
    {
        if($this->getResourceConnection()){

            return $this->getResourceConnection()->getConnection();
        }
    }

    /**
     * @return array
     */
    public function getQuery() : array
    {

        $getConnection = $this->getConnection();
        $tableName = $getConnection->getTableName('Test_DbActions');
        $sql = "SELECT * FROM ". $tableName;




        return  $getConnection->fetchAll( $sql );

    }

    /**
     * @return array
     */
    public function getSaleOrderData(): array
    {
        $tableName = $this->getConnection()->getTableName('sales_order');
        $sql = "SELECT * FROM ". $tableName;


        return  $this->getConnection()->fetchAll( $sql );
    }


    /**
     * @return array
     */
    public function joinSaleOrderTableTestData(): array
    {
        $getConnection = $this->getConnection();
        $select = $getConnection->select()
                    ->from(
                        ['sales_order']
                    )
                    ->joinInner(
                        ['sales_order_address'],
                        'sales_order_address.parent_id=sales_order.entity_id'
                    );
        return $getConnection->fetchAll( $select );
    }

    /**
     * @return array|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerData(): ? array
    {
        $customerCollection = $this->collectionFactory->create();
        $customerCollection->addAttributeToFilter('email', 'noom.html@gmail.com');
        $customerCollection->getSelect()->joinLeft(
            ['ca' => $this->getTable('customer_address_entity')],
            'e.entity_id = ca.parent_id',
            ['city', 'postcode']
        );
        return $customerCollection->getData();
    }

    /**
     * @param string $name
     * @return string
     */
    public function getTable(string $name): string
    {

        $tableName = $this->getConnection()->getTableName($name );
        return $tableName;
    }


}
