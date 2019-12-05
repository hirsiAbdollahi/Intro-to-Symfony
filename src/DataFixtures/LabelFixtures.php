<?php


namespace App\DataFixtures;


use App\Entity\Label;
use Doctrine\Common\Persistence\ObjectManager;

class LabelFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(25, 'label', function () {
            $name = $this->faker->userName;
            $name .= $this->faker->randomElement([
                ' Records', ' Productions', ''
            ]);

            $label = (new Label())
                ->setName($name);

            return $label;
        });

        $manager->flush();
    }
}