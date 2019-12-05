<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Common\Persistence\ObjectManager;

class ArtistFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        // Générer 50 artistes
        $this->createMany(50, 'artist', function (){
            // Construction du nom d'artiste
            $name = $this->faker->randomElement(['DJ ', 'MC ', 'Lil ', ' ']);
            $name .= $this->faker->firstName;
            $name .= $this->faker->randomElement([
                ' ' . $this->faker->realText(10),
                ' aka ' . $this->faker->domainWord,
                ' & The ' . $this->faker->lastName,
                ''
            ]);

            // Instanciation de l'entité
            $artist = (new Artist())
                ->setName($name)
                ->setDescription($this->faker->realText(50));

            return $artist;
        });

        // Enregistrer les entités en BDD

        $manager->flush();
    }
}