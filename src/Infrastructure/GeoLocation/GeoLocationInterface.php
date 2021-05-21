<?php

declare(strict_types=1);

namespace App\Infrastructure\GeoLocation;

interface GeoLocationInterface
{
    /**
     * Get the coordinates of an address and city.
     *
     * @return mixed[]|null containing lat, lng attributes
     */
    public function coordinates(string $address, string $city): ?array;
}
