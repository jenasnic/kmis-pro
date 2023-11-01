<?php

namespace App\Service\Localization;

use App\Domain\Model\Location;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocalizationProvider
{
    public const ENDPOINT = '/geocoding/v5/mapbox.places';

    public function __construct(
        protected HttpClientInterface $mapboxClient,
        protected LocationFactory $locationFactory,
        protected string $mapboxToken,
    ) {
    }

    /**
     * @return array<Location>
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function search(string $location, bool $onlyAddresses = false): array
    {
        $result = $this->mapboxClient->request(
            'GET',
            sprintf('%s/%s.json', self::ENDPOINT, $location),
            [
                'query' => [
                    'access_token' => $this->mapboxToken,
                    'country' => 'FR',
                    'language' => 'fr',
                    'types' => $onlyAddresses ? 'address' : 'region,postcode,place,address',
                ],
            ]
        )->toArray();

        $locations = [];
        foreach ($result['features'] as $item) {
            $locations[] = $this->locationFactory->create($item);
        }

        return $locations;
    }
}
