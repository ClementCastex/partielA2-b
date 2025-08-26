<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $equipments = [
            ['Kayak', 10, 25.00],
            ['Paddle', 8, 15.00],
            ['Vélo', 12, 12.50],
            ['Gilet de sauvetage', 30, 2.00],
            ['Pédalo', 5, 40.00],
        ];

        foreach ($equipments as [$name, $qty, $price]) {
            $e = new Equipment();
            $e->setName($name);
            $e->setQuantityAvailable($qty);
            $e->setPricePerDay($price);
            $manager->persist($e);
        }

        $manager->flush();
    }
}
