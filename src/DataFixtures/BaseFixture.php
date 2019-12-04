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
    /**Liste des references aux entites generees par les fixtures
    */
    private $references=[];

    //Methode à implementer par les classes enfants
    //dans laquelle generer les fausses données

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager =$manager;
        $this->faker = Factory::create('fr_FR');

        //Appel de la methode pour generer les données
        $this->loadData($manager);

    }


    /**
     * Creer plusieurs entites
     * @param int $count nombre d'entites a creer
     * @param string $groupName nom associée aux entites generees
     * @param callable $factory fonction pour creer 1 entit
     */


    protected function createMany(int $count,callable $factory, string $groupName)
    {
        //Executer $factory $count fois
        for ($i=0; $i <$count; $i++){
            // La $factory doit retourner l'entite cree
            $entity =$factory($i);

            if ($entity==null){
                throw new \LogicException('Tu a oublié de retourner l\'entité');
            }

            //Avertir Doctrine pour l'enregistrement de l'entité
            $this->manager->persist($entity);

            //Ajouter une reference pour l'entite
            $this->addReference(sprintf('%s_%d',$groupName,$i), $entity);

        }

    }


    /**
     * Obtenir une entite aleatoire d'un groupe
     */

    protected function getRandomReference(string $groupName)
    {
        // Si les references ne sont pas presentes dans la propriete:
        if (!isset($this->references[$groupName])){
            // Recuperation des references
            foreach ($this->referenceRepository->getReferences() as $key => $ref){
                if (strpos($key,$groupName.'_')===0){
                    $this->references[$groupName][]=$ref;
                }
            }
        }

     //Retourner une reference aleatoire
     $randomReferencekey=  $this->faker->randomElement($this->references[$groupName]);
     return $this->getReference($randomReferencekey);

    }

}