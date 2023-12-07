<?php

namespace DesignPattern\Weathers\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const ENABLED_XML_PATH = 'design_pattern_weathers/general/enabled';
    const OPENWEATHERMAP_API_KEY_XML_PATH = 'design_pattern_weathers/general/openweathermap_api_key';
    const DEFAULT_CITY_XML_PATH = 'design_pattern_weathers/general/default_city';
    const DEFAULT_COUNTRY_ID_XML_PATH = 'design_pattern_weathers/general/default_country_id';

    /** @var ScopeConfigInterface */
    private $config;

    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->config->isSetFlag(
            self::ENABLED_XML_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getOpenWeatherMapApiKey(?int $storeId = null): ?string
    {
        return $this->config->getValue(
            self::OPENWEATHERMAP_API_KEY_XML_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getDefaultCity(?int $storeId = null): ?string
    {
        return $this->config->getValue(
            self::DEFAULT_CITY_XML_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getDefaultCountry(?int $storeId = null): ?string
    {
        return $this->config->getValue(
            self::DEFAULT_COUNTRY_ID_XML_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
