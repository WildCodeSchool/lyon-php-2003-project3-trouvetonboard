<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Level;
use App\Entity\Profile;
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
        // create one profile
        $profile = new profile();
        $profile->setTitle("DRH spéciliste Industre");
        $profile->setIsRequest(true);
        $profile->setIsPropose(false);
        $profile->setPaymentType(2);

        foreach ($values as $key => $categorys) {
            $category = new Category();
            $category->setName($key);
            $manager->persist($category);
            $this->addReference("category_" . $cat, $category);

            for ($a = 0; $a < count($categorys); $a++) {
                for ($i = 0; $i < 5; $i++) {
                    $skill = new Skill();
                    $skill->setName($categorys[$a]);
                    $skill->setCategory($this->getReference("category_" . $cat));
                    $skill->setLevel($this->getReference("level_" . $i));
                    if ($i == 0) {
                        $this->addReference("skill_" . $cat ."_" . $a, $skill);
                    }
                    $manager->persist($skill);

                    if ($i == 0) {
                        $profile->addSkill($this->getReference("skill_" . $cat . "_" . $a));
                    }
                }
            }
            $manager->persist($profile);
            $cat++;
        }
        // enregistrement
        $manager->flush();
    }
}
