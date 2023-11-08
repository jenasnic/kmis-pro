<?php

namespace App\DataFixtures\Quote;

use App\DataFixtures\Factory\Quote\QuoteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        QuoteFactory::createMany(10);
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            OrganizationFixtures::class,
            ServiceFixtures::class,
        ];
    }
}
