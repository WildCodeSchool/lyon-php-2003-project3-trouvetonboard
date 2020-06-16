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
        $admin->setRoles(['ROLE_ADMIN',"ROLE_SUBSCRIBER","ROLE_ENTERRPRISE","ROLE_USER"]);
        $admin->setIsVerified(0);
        $admin->setFirstName("admin");
        $admin->setLastName("last");
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, "pwd"));
        $manager->persist($admin);


        // Création d’un utilisateur de type “cedric”
        $cedric = new User();
        $cedric->setEmail('cedric@ttb.com');
        $cedric->setRoles(['ROLE_ADMIN']);
        $cedric->setIsVerified(0);
        $cedric->setFirstName("admin");
        $cedric->setLastName("last");
        $cedric->setPassword($this->passwordEncoder->encodePassword($cedric, "pwd"));
        $manager->persist($cedric);


        // Création d’un utilisateur de type “user”
        $user = new User();
        $user->setEmail('user@ttb.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(0);
        $user->setFirstName("user");
        $user->setLastName("last");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "pwd"));
        $manager->persist($user);

        // Création d’un utilisateur de type “advisor”
        $advisor = new User();
        $advisor->setEmail('advisor@ttb.com');
        $advisor->setRoles(['ROLE_ADVISOR']);
        $advisor->setIsVerified(0);
        $advisor->setFirstName("advisor");
        $advisor->setLastName("last");
        $advisor->setPassword($this->passwordEncoder->encodePassword($advisor, "pwd"));
        $manager->persist($advisor);

        // Création d’un utilisateur de type “enterprise”
        $enterprise = new User();
        $enterprise->setEmail('enterprise@ttb.com');
        $enterprise->setRoles(['ROLE_ENTERPRISE']);
        $enterprise->setIsVerified(0);
        $enterprise->setFirstName("enterprise");
        $enterprise->setLastName("last");
        $enterprise->setPassword($this->passwordEncoder->encodePassword($enterprise, "pwd"));
        $manager->persist($enterprise);

        // enregistrement
        $manager->flush();
    }
}
