<?php

namespace App\DataFixtures\Factory\Quote;

use App\DataFixtures\Factory\AddressFactory;
use App\Entity\Quote\Quote;
use App\Enum\DurationEnum;
use Faker\Factory;
use Faker\Generator;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Quote>
 */
final class QuoteFactory extends ModelFactory
{
    private Generator $faker;

    public function __construct()
    {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('+5days', '+3months'),
            'duration' => $this->faker->randomElement([DurationEnum::TWO_HOURS, DurationEnum::HALF_DAY, DurationEnum::DAY, DurationEnum::OTHER]),
            'location' => AddressFactory::createOne(),
            'peopleCount' => $this->faker->numberBetween(5, 50),
            'organizationNote' => $this->faker->words(4, true),
            'organization' => OrganizationFactory::random(),
            'serviceNote' => $this->faker->words(4, true),
            'service' => ServiceFactory::random(),
            'contact' => ContactFactory::new(),
            'comment' => $this->faker->text(),
        ];
    }

    protected static function getClass(): string
    {
        return Quote::class;
    }
}
