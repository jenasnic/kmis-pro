<?php

namespace App\DataFixtures\Quote;

use App\DataFixtures\Factory\Quote\ServiceTypeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ServiceTypeFactory::createOne([
            'label' => 'Harcèlement',
            'rank' => 1,
        ]);
        ServiceTypeFactory::createOne([
            'label' => 'Gestion du stress',
            'rank' => 2,
        ]);
        ServiceTypeFactory::createOne([
            'label' => 'Défense personnelle',
            'rank' => 3,
        ]);
    }
}
