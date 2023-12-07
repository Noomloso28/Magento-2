<?php

namespace DesignPattern\Weathers\Api;

interface WeatherServiceInterface
{
    /**
     * @param string $city
     * @param string $countryId
     * @return WeatherApiResponseInterface
     */
    public function getWeatherForCity(?string $city = null, ?string $countryId = null): WeatherApiResponseInterface;
}
