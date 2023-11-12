<?php

namespace App\DataFixtures\Factory\Quote;

use App\Entity\Quote\Contact;
use App\Enum\GenderEnum;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Contact>
 */
final class ContactFactory extends ModelFactory
{
    private Generator $faker;

    private AsciiSlugger $slugger;

    public function __construct()
    {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
        $this->slugger = new AsciiSlugger('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        /** @var GenderEnum $gender */
        $gender = $this->faker->randomElement([GenderEnum::MALE, GenderEnum::FEMALE]);
        $firstName = $this->faker->firstName(strtolower($gender->value));
        $lastName = $this->faker->lastName();

        $email = strtolower(sprintf(
            '%s.%s@yopmail.com',
            $this->slugger->slug($firstName),
            $this->slugger->slug($lastName),
        ));

        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'gender' => $gender,
            'phone' => $this->faker->phoneNumber(),
            'email' => $email,
        ];
    }

    protected static function getClass(): string
    {
        return Contact::class;
    }
}
