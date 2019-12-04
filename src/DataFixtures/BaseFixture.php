<?php


namespace App\DataFixtures;


use doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager*/
    private $manager;
    /** @var Generator*/
    protected $faker;

    //Methode à implementer par les classes enfants
    //dans laquelle generer les fausses données

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager =$manager;
        $this->faker = Factory::create('fr_FR');

        //Appel de la methode pour generer les données
        $this->loaddata($manager);

    }


    /**
     * Creer plusieurs entites
     * @param int $count nombre d'entites a creer
     * @param callable $factory fonction pour creer 1 entite
     */


    protected function createMany(int $count,callable $factory)
    {
        //Executer $factory $count fois
        for ($i=0; $i <$count; $i++){
            // La $factory doit retourner l'entite cree
            $entity =$factory($i);

            if ($entity==null){
                throw new \LogicException('Tu a oublié de retourner');
            }

            //Avertir Doctrine pour l'enregistrement de l'entité
            $this->manager->persist($entity);
        }

    }

}