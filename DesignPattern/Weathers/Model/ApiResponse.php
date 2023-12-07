<?php

namespace DesignPattern\Weathers\Model;

use DesignPattern\Weathers\Api\WeatherApiResponseInterface;
use Psr\Log\LoggerInterface;

class ApiResponse implements WeatherApiResponseInterface
{

    //** @var array */
    private $data;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger,
        array $data = [])
    {
        $this->logger = $logger;
        $this->data = $data;

        $this->logger->debug(implode($data));
    }

    /** @inheirtDoc */
    public function getWeather(): ?string
    {
        return $this->data[self::WEATHER] ?? null;
    }

    /** @inheirtDoc */
    public function getCity(): string
    {
        return $this->data[self::CITY];
    }

    /** @inheirtDoc */
    public function getCountryId(): string
    {
        return $this->data[self::COUNTRY_ID];
    }
}
