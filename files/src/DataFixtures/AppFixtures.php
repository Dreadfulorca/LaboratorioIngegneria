<?php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['Spesa', 'Affitto', 'Trasporti', 'Utenze', 'Svago'] as $name) {
            $c = new Category();
            $c->setName($name);
            $manager->persist($c);
        }
        $manager->flush();
    }
}
