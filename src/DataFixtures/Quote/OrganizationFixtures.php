<?php

namespace App\DataFixtures\Quote;

use App\DataFixtures\Factory\Quote\OrganizationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        OrganizationFactory::createOne([
            'label' => 'Particulier',
            'rank' => 1,
        ]);
        OrganizationFactory::createOne([
            'label' => 'École',
            'rank' => 2,
        ]);
        OrganizationFactory::createOne([
            'label' => 'Entreprise',
            'rank' => 3,
        ]);
        OrganizationFactory::createOne([
            'label' => 'Administration',
            'rank' => 4,
        ]);
        OrganizationFactory::createOne([
            'label' => 'Autre',
            'rank' => 5,
            'withNote' => true,
        ]);
    }
}
