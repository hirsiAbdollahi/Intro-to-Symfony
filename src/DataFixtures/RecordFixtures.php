<?php


namespace App\DataFixtures;



use App\Entity\Note;
use App\Entity\Record;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RecordFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'record', function ($i) {
            $record = (new Record())
                ->setTitle($this->faker->realText('50'))
                ->setDescription($this->faker->text)
                ->setReleasedAt($this->faker->dateTimeBetween('-1 year'))
                ->setArtist($this->getRandomReference('artist'))
            ;

            // 75% des albums auront un Label de défini
            if ($this->faker->boolean(75)) {
                $record->setLabel($this->getRandomReference('label'));
            }



            // Création des notes de l'album

            // Récupération des utilisateurs et élimination des doublons
            $nbUsers = $this->faker->numberBetween(0, 10);
            $users = $this->getRandomReferences('user_user', $nbUsers);
            $users = array_unique($users);

            // Création des notes
            foreach ($users as $user) {
                $createdAt = $this->faker->dateTimeBetween($record->getReleasedAt());

                $note = (new Note())
                    ->setUser($user)
                    ->setValue($this->faker->numberBetween(0, 10))
                    ->setCreatedAt($createdAt)
                ;

                // 75% des notes ont un commentaire
                if ($this->faker->boolean(75)) {
                    $note->setComment($this->faker->realText());
                }

                // Ajout de la note au Record
                $record->addNote($note);
            }

            return $record;
        });

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            ArtistFixtures::class,
            LabelFixtures::class,
        ];
    }
}