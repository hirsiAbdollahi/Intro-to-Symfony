<?php

namespace App\DataFixtures;


use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Artist;

class ArtistFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        // generer 50 artistes
        $this->createMany(50,function() {
            //Construction du nom d'artiste
            $name=$this->faker->randomElement(['DJ ', 'MC ', 'Lil ', '']);
            $name.=$this->faker->firstName;
            $name.=$this->faker->randomElement([
                '' .$this->faker->realText('10'),
                ' aka ' .$this->faker->domainWord,
                ' & The ' .$this->faker->lastName,
            ]);
            // Instanciation de l'entite
            $artist = (new Artist())
            ->setName($name)
            ->setDescription($this->faker->realText(50));

            //Retourner l'entite
            return $artist;
        });

        //Enregistrer les entites en BDD
        $manager->flush();
    }
}
