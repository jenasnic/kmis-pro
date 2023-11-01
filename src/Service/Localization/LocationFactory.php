<?php

namespace App\Service\Localization;

use App\Domain\Model\Location;
use App\ValueObject\Coordinates;

/**
 * Factory used to create location from Mapbox API response.
 *
 * @see https://docs.mapbox.com/api/search/geocoding/
 */
class LocationFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Location
    {
        $placeTypes = $this->extractAsArray($data, 'place_type');

        switch (true) {
            case in_array('region', $placeTypes):
                return $this->createRegion($data);
            case in_array('postcode', $placeTypes):
                return $this->createDepartment($data);
            case in_array('place', $placeTypes):
                return $this->createCity($data);
            case in_array('address', $placeTypes):
                return $this->createAddress($data);
        }

        throw new \LogicException('Unknown place type!');
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function createRegion(array $data): Location
    {
        $location = new Location();

        if (!isset($data['properties']) || !is_array($data['properties'])) {
            throw new \LogicException('Invalid data');
        }

        $this->processRegionData($location, $this->extractAsString($data, 'text_fr'), $data['properties']['short_code'] ?? null);
        $this->addCoordinates($location, $data);

        return $location;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function createDepartment(array $data): Location
    {
        $location = new Location();

        $zipCode = $this->extractAsString($data, 'text_fr');

        $location->zipCode = $zipCode;
        $location->department = DepartmentResolver::departmentForZip($zipCode);
        $location->region = DepartmentResolver::regionForZip($zipCode);

        $this->fillWithContext($location, $this->extractAsArray($data, 'context'));
        $this->addCoordinates($location, $data);

        return $location;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function createCity(array $data): Location
    {
        $location = new Location();

        $location->city = $this->extractAsString($data, 'text_fr');
        $this->fillWithContext($location, $this->extractAsArray($data, 'context'));
        $this->addCoordinates($location, $data);

        return $location;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function createAddress(array $data): Location
    {
        $location = new Location();

        $location->street = $this->extractAsString($data, 'text_fr');
        if (array_key_exists('address', $data)) {
            $location->street = sprintf('%s %s', $this->extractAsString($data, 'address'), $location->street);
        }

        $this->fillWithContext($location, $this->extractAsArray($data, 'context'));
        $this->addCoordinates($location, $data);

        return $location;
    }

    /**
     * @param array<string, mixed> $context
     */
    private function fillWithContext(Location $location, array $context): void
    {
        $postcodeFound = false;
        foreach ($context as $data) {
            if (!is_array($data)) {
                throw new \LogicException('Invalid data');
            }

            $id = $this->extractAsString($data, 'id');
            $textFR = $this->extractAsString($data, 'text_fr');

            if (str_starts_with($id, 'postcode')) {
                $location->zipCode = $textFR;
                $location->department = DepartmentResolver::departmentForZip($textFR);
                $location->region = DepartmentResolver::regionForZip($textFR);
                $postcodeFound = true;

                continue;
            }

            if (str_starts_with($id, 'region') && !$postcodeFound) {
                $this->processRegionData($location, $textFR, $data['short_code'] ?? null);

                continue;
            }

            if (str_starts_with($id, 'place')) {
                $location->city = $textFR;
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function addCoordinates(Location $location, array $data): void
    {
        if (array_key_exists('geometry', $data) && is_array($data['geometry']) && array_key_exists('coordinates', $data['geometry'])) {
            if (!is_array($data['geometry']['coordinates']) || !is_float($data['geometry']['coordinates'][0]) || !is_float($data['geometry']['coordinates'][1])) {
                throw new \LogicException('Invalid data');
            }
            $location->coordinates = new Coordinates($data['geometry']['coordinates'][0], $data['geometry']['coordinates'][1]);
        }
    }

    private function processRegionData(Location $location, string $value, ?string $shortCode = null): void
    {
        // specific case for DOM TOM
        if (null === $shortCode) {
            $location->department = $value;

            return;
        }

        if ($this->isCodeRegion($shortCode)) {
            $location->region = $value;
        } elseif ($this->isCodeDepartment($shortCode)) {
            $location->department = $value;
            $location->region = DepartmentResolver::regionForDepartment($value);
        }
    }

    private function isCodeRegion(string $code): bool
    {
        return 1 === preg_match('/FR-([a-zA-Z]+)/', $code);
    }

    private function isCodeDepartment(string $code): bool
    {
        return 1 === preg_match('/FR-([0-9]+)/', $code);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function extractAsString(array $data, string $key): string
    {
        if (!isset($data[$key]) || !is_string($data[$key])) {
            throw new \LogicException('Invalid data');
        }

        return $data[$key];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function extractAsArray(array $data, string $key): array
    {
        if (!isset($data[$key]) || !is_array($data[$key])) {
            throw new \LogicException('Invalid data');
        }

        return $data[$key];
    }
}
