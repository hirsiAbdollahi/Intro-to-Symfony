<?php


namespace App\DataFixtures;



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