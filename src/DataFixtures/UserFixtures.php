<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // Creation d’un utilisateur de type “admin”
        $admin = new User();
        $admin->setEmail('admin@ttb.com');
        $admin->setRoles(['ROLE_ADMIN',"ROLE_SUBSCRIBER","ROLE_ENTERRPRISE","ROLE_USER"]);
        $admin->setIsVerified(0);
        $admin->setFirstName("admin");
        $admin->setLastName("last");
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, "pwd"));
        $admin->setFirstName($faker->firstName);
        $admin->setLastName($faker->lastName);
        $manager->persist($admin);


        // Creation d’un utilisateur de type “cedric”
        $cedric = new User();
        $cedric->setEmail('cedric@ttb.com');
        $cedric->setRoles(['ROLE_ADMIN']);
        $cedric->setIsVerified(0);
        $cedric->setFirstName("admin");
        $cedric->setLastName("last");
        $cedric->setPassword($this->passwordEncoder->encodePassword($cedric, "pwd"));
        $cedric->setFirstName($faker->firstName);
        $cedric->setLastName($faker->lastName);
        $manager->persist($cedric);


        // Creation d’un utilisateur de type “user”
        $user = new User();
        $user->setEmail('user@ttb.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(0);
        $user->setFirstName("user");
        $user->setLastName("last");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "pwd"));
        $user->setFirstName($faker->firstName);
        $user->setLastName($faker->lastName);
        $manager->persist($user);

        // Creation d’un utilisateur de type “advisor”
        $advisor = new User();
        $advisor->setEmail('advisor@ttb.com');
        $advisor->setRoles(['ROLE_ADVISOR']);
        $advisor->setIsVerified(0);
        $advisor->setFirstName("advisor");
        $advisor->setLastName("last");
        $advisor->setPassword($this->passwordEncoder->encodePassword($advisor, "pwd"));
        $manager->persist($advisor);

        // Creation d’un utilisateur de type “enterprise”
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
