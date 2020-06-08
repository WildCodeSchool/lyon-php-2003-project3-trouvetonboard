<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Création d’un utilisateur de type “admin”
        $admin = new User();
        $admin->setEmail('admin@ttb.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, "pwd"));
        $manager->persist($admin);


        // Création d’un utilisateur de type “cedric”
        $cedric = new User();
        $cedric->setEmail('cedric@ttb.com');
        $cedric->setRoles(['ROLE_ADMIN']);
        $cedric->setPassword($this->passwordEncoder->encodePassword($cedric, "pwd"));
        $manager->persist($cedric);

        // Création d’un utilisateur de type “user”
        $user = new User();
        $user->setEmail('user@ttb.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, "pwd"));
        $manager->persist($user);


        // enregistrement
        $manager->flush();
    }
}
