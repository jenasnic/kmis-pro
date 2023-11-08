<?php

namespace App\DataFixtures\Factory;

use App\ValueObject\Address;
use Faker\Provider\fr_FR\Address as FakerAddress;
use Faker\Factory;

final class AddressFactory
{
    /**
     * @var array<string>
     */
    private static array $regions = [
        'Auvergne-Rhône-Alpes',
        'Bourgogne-Franche-Comté',
        'Bretagne',
        'Centre-Val de Loire',
        'Corse',
        'Grand Est',
        'Hauts-de-France',
        'Île-de-France',
        'Normandie',
        'Nouvelle-Aquitaine',
        'Occitanie',
        'Pays de la Loire',
        'Provence-Alpes-Côte d\'Azur',
    ];

    /**
     * @param array<string, mixed> $attributes
     */
    final public static function createOne(array $attributes = []): Address
    {
        $faker = Factory::create('fr_FR');

        $department = FakerAddress::department();
        $departmentNumber = (string) array_key_first($department);
        $departmentValue = $department[$departmentNumber];

        $defaultAttributes = [
            'street' => sprintf('%u %s %s %s', $faker->numberBetween(1, 50), FakerAddress::streetPrefix(), $faker->firstName(), $faker->lastName()),
            'zipCode' => str_pad($departmentNumber, 5, '0'),
            'city' => $faker->city(),
            'department' => $departmentValue,
            'region' => $faker->randomElement(self::$regions),
            'country' => 'FRANCE',
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        return new Address(
            $attributes['street'],
            $attributes['zipCode'],
            $attributes['city'],
            $attributes['department'],
            $attributes['region'],
            $attributes['country'],
        );
    }
}
