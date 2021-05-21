<?php

declare(strict_types=1);

namespace App\Infrastructure\GeoLocation;

class GoogleGeoLocation implements GeoLocationInterface
{
    private string $googleMapKey;

    public function __construct(string $googleMapKey)
    {
        $this->googleMapKey = $googleMapKey;
    }

    /**
     * Get the coordinates of an address and city.
     *
     * @return mixed[]|null containing lat, lng attributes
     */
    public function coordinates(string $address, string $city): ?array
    {
        $address2 = str_replace(' ', '+', $address.', '.$city);
        $address2 = preg_replace('/\s+/', ' ', $address2);
        $key = $this->googleMapKey;
        $url = 'https://maps.google.com/maps/api/geocode/json?sensor=false&address='.urlencode($address2)."&key={$key}";
        $response = file_get_contents($url);
        $json = json_decode($response, true);
        if (!empty($json) && !empty($json['status']) && 'OK' === $json['status']) {
            return [
                'lat' => $json['results'][0]['geometry']['location']['lat'],
                'lng' => $json['results'][0]['geometry']['location']['lng'],
            ];
        }

        return null;
    }
}
