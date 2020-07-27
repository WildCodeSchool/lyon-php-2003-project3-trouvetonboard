<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {

        // Fixtures for pre-prodution base isert , comment for test or dev environement
        $cedric = new User();        //  static user cedric
        $cedric->setEmail('ced.delannoy@free.fr');
        $cedric->setRoles(['ROLE_ADMIN']);
        $cedric->setIsVerified(0);
        $cedric->setType("Admin");
        $cedric->setFirstName("Cedric");
        $cedric->setLastName("Delannoy");
        // file is versionned but real-mdp  is not versionned
        $cedric->setPassword($this->passwordEncoder->encodePassword($cedric, "****"));
        $manager->persist($cedric);
        $manager->flush();
    }
}
