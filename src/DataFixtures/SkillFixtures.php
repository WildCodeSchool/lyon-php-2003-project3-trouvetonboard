<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Level;
use App\Entity\Skill;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class SkillFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        $values = [
            "Type de missions" => ["Payantes", "Bénévoles", "Bénévoles puis payantes"],
            "Rémunération" => ["Salaire", "defraiement", "Equity"],
            "Taille de l'entreprise" => ["Idéation", "Start-up", "TPE", "PME", "ETI", "Grand groupes"],
        ];
        // Création du  level 1
        $cat = 0;
        $skill = 0;
        foreach ($values as $key => $categorys) {
            $category = new Category();
            $category->setName($key);
            var_dump($key);
            $manager->persist($category);
            $this->addReference("category_" . $cat, $category);

            var_dump($category);
            for ($a = 0; $a < count($categorys); $a++) {
                for ($i = 0; $i < 5; $i++) {
                    $skill = new Skill();
                    $skill->setName($categorys[$a]);
                    $skill->setCategory($this->getReference("category_" . $cat));
                    $skill->setLevel($this->getReference("level_" . $i));
                    $manager->persist($skill);
                }
            }
            $cat++;
        }
        // enregistrement
        $manager->flush();
    }
}
