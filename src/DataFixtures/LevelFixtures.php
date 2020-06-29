<?php

namespace App\DataFixtures;

use App\Entity\Level;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class LevelFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        $value = ["Inconnu", "Notions", "Debutant", "Intermediaire", "Expert"];
        // Création du  level 1
        for ($a = 0; $a < count($value); $a++) {
            $level = new Level();
            $level->setName($value[$a]);
            $level->setLevel($a);
            $manager->persist($level);
            $this->addReference("level_" . $a, $level);
        }
        // enregistrement
        $manager->flush();
    }
}
