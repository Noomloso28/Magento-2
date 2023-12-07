<?php

namespace DesignPattern\Weathers\Api;

interface WeatherApiResponseInterface
{

    const WEATHER = 'weather';
    const CITY = 'city';
    const COUNTRY_ID = 'country_id';

    /**
     * Returns description of atmospheric conditions
     *
     * @return string|null
     */
    public function getWeather() : ?string;

    /** @return string */
    public function getCity() : string;

    /** @return string */
    public function getCountryId() : string;
}
