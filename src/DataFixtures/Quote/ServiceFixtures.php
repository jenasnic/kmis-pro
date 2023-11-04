<?php

namespace App\DataFixtures\Quote;

use App\DataFixtures\Factory\Quote\ServiceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ServiceFactory::createOne([
            'label' => 'Harcèlement',
            'rank' => 1,
        ]);
        ServiceFactory::createOne([
            'label' => 'Gestion du stress',
            'rank' => 2,
        ]);
        ServiceFactory::createOne([
            'label' => 'Défense personnelle',
            'rank' => 3,
        ]);
        ServiceFactory::createOne([
            'label' => 'Autre',
            'rank' => 4,
            'withNote' => true,
        ]);
    }
}
