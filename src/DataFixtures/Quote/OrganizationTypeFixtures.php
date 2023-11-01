<?php

namespace App\DataFixtures\Quote;

use App\DataFixtures\Factory\Quote\OrganizationTypeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizationTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        OrganizationTypeFactory::createOne([
            'label' => 'Particulier',
            'rank' => 1,
        ]);
        OrganizationTypeFactory::createOne([
            'label' => 'École',
            'rank' => 2,
        ]);
        OrganizationTypeFactory::createOne([
            'label' => 'Entreprise',
            'rank' => 3,
        ]);
        OrganizationTypeFactory::createOne([
            'label' => 'Administration',
            'rank' => 4,
        ]);
    }
}
