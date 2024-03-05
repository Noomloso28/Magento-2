<?php
/**
 * Customer importer.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer;

use Immerce\FaktumNavConnector\FieldMapper\FieldMapperInterface;
use Immerce\FaktumNavConnector\Model\AttributeData;
use Immerce\FaktumNavConnector\ValueParser\Factory\ValueParserFactory;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir\Reader as ModuleDirReader;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ObjectManager;
use Psr\Log\LoggerInterface;
use Magento\Framework\Validator\EmailAddress;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Store\Api\StoreRepositoryInterface;

/**
 * Customer importer class.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @since   1.0.0
 */
class CustomerImporter extends AbstractImporter
{
    /**
     * @var ValueParserFactory
     */
    private ValueParserFactory $valueParserFactory;

    /**
     * @var EavConfig
     */
    private EavConfig $eavConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CustomerFactory
     */
    private CustomerFactory $customerFactory;

    /**
     * @var Customer
     */
    private Customer $customer;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var EmailAddress
     */
    private EmailAddress $emailAddress;

    /**
     * @var WebsiteRepositoryInterface
     */
    private WebsiteRepositoryInterface $websiteRepository;

    /**
     * @var StoreRepositoryInterface
     */
    private StoreRepositoryInterface $storeRepository;


    public function __construct(
        FieldMapperInterface $fieldMapper,
        ModuleDirReader $moduleDirReader,
        AttributeData $attributeData,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        ValueParserFactory $valueParserFactory,
        EavConfig $eavConfig,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        Customer $customer,
        LoggerInterface $logger,
        EmailAddress $emailAddress,
        WebsiteRepositoryInterface $websiteRepository,
        StoreRepositoryInterface $storeRepository
    ) {

        parent::__construct($fieldMapper, $moduleDirReader, $attributeData, $directoryList, $filesystem);
        $this->fieldMapper->loadDefinition('customer');
        $this->attributeData->load('customer');
        $this->valueParserFactory = $valueParserFactory;
        $this->eavConfig = $eavConfig;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->customer = $customer;
        $this->logger = $logger;
        $this->emailAddress = $emailAddress;
        $this->websiteRepository = $websiteRepository;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @inheritDoc
     */
    public function import(array $data): bool
    {

        $this->data = $data;
        $i = 1;
        $count = count($data);
        $websiteId = (int) $this->websiteRepository->get('base')->getId();
        $storeId = (int) $this->storeRepository->get('default')->getId();


        while ($this->nextItem()) {

            $customerId = null;
            $this->currentItem = array_filter($this->currentItem);

            /** check if no have email address not process */
            if( !array_key_exists('email', $this->currentItem) ){
                $this->logger->debug('No have email address');
                $i++;
                continue;
            }

            /** check exit user */
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($this->currentItem['email']);

            if (!$customerId = $customer->getId()) {
                /**  $customer create new user */
                $customer = $this->customerFactory->create()->setWebsiteId($websiteId);
                $customer->setPassword($this->generatePassword(20));

                $this->logger->debug("Create new customer account by email (".$this->currentItem['email'].") starting ...");

                /** add new customer condition */
                if(!$this->emailAddress->isValid($this->currentItem['email'])){
                    $this->logger->debug("Error !!! The email address (".$this->currentItem['email'].") wrong format.");
                    $i++;
                    continue;
                }

                //Vorname require for create new.
                if(!isset($this->currentItem['firstname'])){
                    $this->logger->debug("Error !!! For create new customer the firstname field required.");
                    $i++;
                    continue;
                }
                if(!isset($this->currentItem['lastname'])){
                    $this->logger->debug("Error !!! For create new customer the lastname field required.");
                    $i++;
                    continue;
                }
            }else{
                $this->logger->debug("Update exits customer account (".$this->currentItem['email'].") starting ...");
            }

            if ($customer instanceof Customer) {
                // set store ID
                $customer->setStoreId($storeId);

                !isset($this->currentItem['email']) ?: $customer->setEmail($this->currentItem['email']);
                !isset($this->currentItem['firstname']) ?: $customer->setFirstname($this->currentItem['firstname']);
                !isset($this->currentItem['lastname']) ?: $customer->setLastname($this->currentItem['lastname']);
                !isset($this->currentItem['middlename']) ?: $customer->setMiddlename($this->currentItem['middlename']);
                !isset($this->currentItem['group_id']) ?: $customer->setGroupId($this->currentItem['group_id']);
                !isset($this->currentItem['prefix']) ?: $customer->setPrefix($this->currentItem['prefix']);
                !isset($this->currentItem['suffix']) ?: $customer->setSuffix($this->currentItem['suffix']);
                !isset($this->currentItem['gender']) ?: $customer->setGender($this->currentItem['gender']);
                // Import customer attributes, if there are any.
                if (array_key_exists('attributes', $this->currentItem)) {
                    $this->currentItem['attributes'] = $this->fieldMapper->map($this->currentItem['attributes']);
                    //remove empty value
                    $attributes = array_filter($this->currentItem['attributes']);

                    //Nav customer id require
                    if (!$customerId){ // in case update use old value
                        $attributes['nav_cust_id'] = $attributes['nav_cust_id'] ?? -1;
                    }

                    $customer = $this->importAttributes($customer, $attributes);
                }
                /** clear address before add new */
                if($customerId){
                    $this->cleanAllAddress($customer);
                }

                // Save data
                $customer->save();

                /** add address, need customerId for set billAdresss default */
                if (array_key_exists('addresses', $this->currentItem)) {
                    $customer->getAddresses();
                    foreach ($this->currentItem['addresses'] as $address) {
                        $this->setAddress($customer, $address, $this->currentItem);
                    }
                }

            }
            echo $this->progressBar($i, $count) ;
            $i++;
        }
        return true;
    }

    /**
     * @param Customer $customer
     * @param array $attributes
     * @return Customer
     */
    protected function importAttributes(Customer $customer, array $attributes): Customer
    {
        foreach ($attributes as $code => $value) {
            $attributeType = $this->attributeData->getAttributeType($code);
            $value = $this->valueParserFactory->create($attributeType)->parse($value);
            if (isset($value)) {
                $customer->setData($code, $value);
            }
        }

        return $customer;
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    private function cleanAllAddress(Customer $customer): Customer
    {
        /** @var Address $customerAddresses */
        $customerAddresses = $customer->getAddresses() ?? [];
        $objectManager = ObjectManager::getInstance();
        $addressData = $objectManager->get(\Magento\Customer\Api\AddressRepositoryInterface::class);

        foreach ($customerAddresses as $address) {
            try {
                $addressId = $address->getData('entity_id');
                $addressData->deleteById($addressId);
            } catch(\Exception $e) {
            }
        }

        return $customer;
    }

    /**
     * @param Customer $customer
     * @param array $attributes
     * @return Customer
     * @throws LocalizedException
     */
    private function setAddress(Customer $customer, array $attributes, array $currentItem): Customer
    {
        $objectManager = ObjectManager::getInstance();
        $address = $objectManager->get(Address::class);
        $details = $attributes['_value'] ?? $attributes;
        $default = $attributes['_attribute'] ?? [];
        $data = [];

        if (isset($details['company'])) {
            $data['company'] = $details['company'];
        }

        if (isset($details['prefix'])) {
            $data['prefix'] = $details['prefix'];
        }

        /**  Optional. Wird von den Daten oben übernommen, falls nicht vorhanden */
        !isset($currentItem['firstname']) ?:  $data['firstname'] = $currentItem['firstname'];
        if (isset($details['firstname'])) {
            $data['firstname'] = $details['firstname'];
        }

        /** Optional. Wird von den Daten oben übernommen, falls nicht vorhanden. */
        !isset($currentItem['middlename']) ?:  $data['middlename'] = $currentItem['middlename'];
        if (isset($details['middlename'])) {
            $data['middlename'] = $details['middlename'];
        }

        /** Optional. Wird von den Daten oben übernommen, falls nicht vorhanden. */
        !isset($currentItem['lastname']) ?:  $data['lastname'] = $currentItem['lastname'];
        if (isset($details['lastname'])) {
            $data['lastname'] = $details['lastname'];
        }

        /** Optional. Wird von den Daten oben übernommen, falls nicht vorhanden. */
        !isset($currentItem['suffix']) ?:  $data['suffix'] = $currentItem['suffix'];
        if (isset($details['suffix'])) {
            $data['suffix'] = $details['suffix'];
        }

        /** set street default */
        $data['street'] = [];
        if (isset($details['street'])) {

            $street[] = $details['street'];
            if (isset($details['street_line_2'])) {
                $street[] = $details['street_line_2'];
            }
            if (isset($details['street_line_3'])) {
                $street[] = $details['street_line_3'];
            }
//            $address->setStreetFull($street);
            $data['street'] = $street;
        }

        if (isset($details['city'])) {
            $data['city'] = $details['city'];
        }

        if (isset($details['country_id'])) {
            $data['country_id'] = $details['country_id'];
        }

        if (isset($details['telephone'])) {
            $data['telephone'] = $details['telephone'];
        }

        if (isset($details['fax'])) {
            $data['fax'] = $details['fax'];
        }

        if (isset($details['vat_id'])) {
            $data['vat_id'] = $details['vat_id'];
        }

        if (isset($details['region_id'])) {
            $data['region_id'] = $details['region_id'];
        }

        if (isset($details['postcode'])) {
            $data['postcode'] = $details['postcode'];
        }

        if (isset($default['default-shipping']) && $default['default-shipping'] == true) {
            //$address->setIsDefaultShipping(1);
            $data['default_shipping'] = 1;
        }

        if (isset($default['default-billing']) && $default['default-billing'] == true) {
//            $address->setIsDefaultBilling(1);
            $data['default_billing'] = 1;
        }

        if (array_key_exists('attributes', $details)) {
            if (isset($details['attributes']['faktum_address_type'])) {
                $data['custom_select_type'] = $details['attributes']['faktum_address_type'];
            }
            if (isset($details['attributes']['faktum_contact_person'])) {
                $data['input_other'] = $details['attributes']['faktum_contact_person'];
            }
        }

        $address->setData($data);
        // Assign customer and address
        $customer->addAddress($address);
        $customer->save();
        unset($data);

        // Mark last address as default billing and default shipping for current customer
        if (isset($default['default-shipping']) && $default['default-shipping'] == true) {
            $customer->setDefaultShipping($address->getId());
        }
        if (isset($default['default-billing']) && $default['default-billing'] == true) {
            $customer->setDefaultBilling($address->getId());
        }
        $customer->save();

        return $customer;
    }

    protected function generatePassword(
        $length,
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $str;
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
