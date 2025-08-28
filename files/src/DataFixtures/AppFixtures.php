<?php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Nome => Colore (#RRGGBB)
        $categories = [
            'Affitto'    => '#C2185B', // rosa scuro
            'Spesa'      => '#4CAF50', // verde
            'Svago'      => '#FF9800', // arancione
            'Trasporti'  => '#2196F3', // blu
            'Utenze'     => '#9C27B0', // viola

        ];

        foreach ($categories as $name => $color) {
            $c = new Category();
            $c->setName($name);
            $c->setColor($color); // <— nuovo campo
            $manager->persist($c);
        }

        $manager->flush();
    }
}
