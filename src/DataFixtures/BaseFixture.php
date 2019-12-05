<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private $manager;
    /** @var Generator */
    protected $faker;
    /** Liste des références aux entités générées par les fixtures */
    private $references = [];

    // Méthode à implémenter par les classes enfant dans laquelle générer les fausses données
    private $referenceRepositor;

    abstract protected function loadData(ObjectManager $manager);

    // Méthode imposée par Doctrine
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');

        // Appel de la méthode pour généreer les données
        $this->loadData($manager);
    }

    /**
     * Créer plusieurs entités
     * @param int $count nombre d'entités à créer
     * @param string $groupName nom associé aux entités générées
     * @param callable $factory fonction pour créer l'entité
     */

    protected function createMany(int $count, string $groupName, callable $factory)
    {
        // Exécuter $factory $count fois
        for ($i=0; $i<$count; $i++)
        {
            // La $factory doit retourner l'entite créée
            $entity = $factory($i);

            if ($entity === null)
            {
                throw new \LogicException('Tu as oublié de retourner l\'entité !!!');
            }

            // Avertir doctrine pour l'enregistrement de l'entite
            $this->manager->persist($entity);

            // Ajouter une référence pour l'entité
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);

        }
    }

    /**
     * Obtenir une entité aléatoire d'un groupe
     * @throws \Exception
     */

    protected function getRandomReference(string $groupName)
    {
        // Si les références ne sont pas présentes dans la propriété:
        if(!isset($this->references[$groupName])) {
            // Récupération des références
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $groupName . '_') === 0) {
                    $this->references[$groupName][] = $key;
                }
            }
        }

        // Vérifier que des références ont été enregistrées
        if(!isset($this->references[$groupName]) || empty($this->references[$groupName])) {
            throw new \Exception(sprintf('Aucune référence trouvée pour "%s"', $groupName));
        }


        // Retourner une référence aléatoire
        $randomReferenceKey = $this->faker->randomElement($this->references[$groupName]);

        return $this->getReference($randomReferenceKey);
    }

    /**
     * Récupérer plusieurs entités
     */

    protected function getRandomReferences(string $groupName, int $amount)
    {
        $references = [];
        while (count($references)<$amount){
            $references[] = $this->getRandomReference($groupName);
        }

        return $references;
    }
}