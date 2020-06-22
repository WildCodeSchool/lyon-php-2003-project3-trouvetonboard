<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Level;
use App\Entity\Profile;
use App\Entity\Skill;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class SkillFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_MAX_SKIILS = 11;


    public function load(ObjectManager $manager)
    {
        $values = [
            "Type de missions" =>
                [
                    "question=Quel type de mission d’advisor proposez-vous ?",
                    "Payantes",
                    "Bénévoles",
                    "Bénévoles puis payantes"
                ],
            "Rémunération" =>
                [
                    "question=Comment souhaitez-vous rémunérer votre advisor ?",
                    "Salaire",
                    "defraiement",
                    "Equity"
                ],
            "Taille de l'entreprise" =>
                [
                    "question=Vous recherchez un advisor pour votre entreprise * 
                    ou pour un nouveau projet au stade de ?",
                    "Idéation",
                    "Start-up",
                    "TPE",
                    "PME",
                    "ETI",
                    "Grand groupes"
                ],
        ];
        // Création du  level 1
        $cat = 0;
        $skillNb = 0;
        foreach ($values as $key => $categorys) {
            $category = new Category();
            $category->setName($key);
            var_dump($key);
            $manager->persist($category);
            $this->addReference("category_" . $cat, $category);
            var_dump($category);
            for ($a = 0; $a < count($categorys); $a++) {
                $search = 'question=';
                if (preg_match("/{$search}/i", $categorys[$a])) {
                    $category->setQuestion(str_replace($search, "", $categorys[$a]));
                } else {
                    for ($i = 0; $i < 5; $i++) {
                        $skill = new Skill();
                        $skill->setName($categorys[$a]);
                        $skill->setCategory($this->getReference("category_" . $cat));
                        $skill->setLevel($this->getReference("level_" . $i));
                        if ($i == 0) {
                            $this->addReference("skill_" . $cat . "_" . $a, $skill);
                            $this->addReference("skillNb_$skillNb", $skill);
                            $skillNb++;
                        }
                        $manager->persist($skill);
                    }
                }
            }
            $cat++;
        }
        // enregistrement
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LevelFixtures::class,];
    }
}
