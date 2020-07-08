<?php

namespace App\DataFixtures;

use App\Entity\Advisor;
use App\Entity\Enterprise;
use App\Entity\Profile;
use App\Entity\Skill;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

/**
 * Class UserFixtures
 * @SuppressWarnings(PHPMD)
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    // static nb ref for addref static users
    const NB_REF_ADMIN = 1;
    const NB_REF_CEDRIC = 2;
    // fix the number of advisor to create , number is end - start
    const NB_REF_START_ADVISOR = 100;
    const NB_REF_END_ADVISOR = 130;
    // fix the number of board request by enterprise profile
    const NB_PROFILE_BY_ENTERPRISE = 5;
    const NB_REF_START_ENTERPRISE = 1000;
    const NB_REF_END_ENTERPRISE = 1049;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $admin = new User();  // static user  “admin”
        $admin->setEmail('admin@ttb.com');
        $admin->setRoles(['ROLE_ADMIN', "ROLE_SUBSCRIBER", "ROLE_ENTERRPRISE", "ROLE_USER"]);
        $admin->setIsVerified(0);
        $admin->setFirstName("admin");
        $admin->setType("Admin");
        $admin->setLastName("last");
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, "pwd"));
        $admin->setFirstName($faker->firstName);
        $admin->setLastName($faker->lastName);
        $manager->persist($admin);
        $numRefAdmin = self::NB_REF_ADMIN;
        $this->addReference("user_$numRefAdmin", $admin);
        $cedric = new User();        //  static user cedric
        $cedric->setEmail('cedric@ttb.com');
        $cedric->setRoles(['ROLE_ADMIN']);
        $cedric->setIsVerified(0);
        $cedric->setType("Admin");
        $cedric->setFirstName("admin");
        $cedric->setLastName("last");
        $cedric->setPassword($this->passwordEncoder->encodePassword($cedric, "pwd"));
        $cedric->setFirstName($faker->firstName);
        $cedric->setLastName($faker->lastName);
        $manager->persist($cedric);
        $numRefAdmin = self::NB_REF_CEDRIC;
        $this->addReference("user_$numRefAdmin", $cedric);
        $gender = ["Homme", "Femme", "Autre"];
        for ($i = self::NB_REF_START_ADVISOR; $i < self::NB_REF_END_ADVISOR; $i++) {
            $advisor = new Advisor();
            $advisor->setPaymentStatus(1);
            $advisor->setLinkedinLink($faker->url);
            $advisor->setIsAlreadyBoardMember(rand(0, 1));
            $manager->persist($advisor);
            $this->addReference("advisor_$i", $advisor);
            $user = new User();
            $status = $i - self::NB_REF_START_ADVISOR;
            $user->setEmail("user$status@ttb.com");
            $user->setRoles(['ROLE_USER', "ROLE_ADVISOR"]);
            $user->setIsVerified(1);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setPictureLink($faker->imageUrl());
            $user->getPostCode($faker->postcode);
            $user->setGender($gender[rand(0, 2)]);
            $user->setCity($faker->city);
            $user->setAddress($faker->address);
            $user->setType("Advisor");
            $user->setPassword($this->passwordEncoder->encodePassword($user, "pwd"));
            $manager->persist($user);
            $this->addReference("user_$i", $user);
            $profile = new Profile();
            $profile->setPaymentType("All");
            $profile->setTitle($faker->jobTitle);
            $profile->setIsPropose(true);
            $profile->setIsRequest(false);
            $profile->setDateCreation($faker->dateTime);
            $this->addReference("profile_$i", $profile);
            $manager->persist($profile);
            $user->setAdvisor($this->getReference("advisor_$i"));
            $advisor->addProfile($this->getReference("profile_$i"));
            for ($k = 0; $k < rand(1, SkillFixtures::NB_MAX_SKIILS); $k++) {
                $profile->addSkill($this->getReference("skillNb_$k"));
            }
        }
        $enterpriseUser = new User(); // Enterprise type user creation for dev , no  loop  , just one at this time
        $enterpriseUser->setEmail('enterprise@ttb.com');
        $enterpriseUser->setRoles(['ROLE_ENTERPRISE']);
        $enterpriseUser->setIsVerified(0);
        $enterpriseUser->setFirstName("Wilding");
        $enterpriseUser->setLastName("Coder");
        $enterpriseUser->setPassword($this->passwordEncoder->encodePassword($enterpriseUser, "pwd"));
        $enterpriseUser->setType("Entreprise");

        $manager->persist($enterpriseUser);
        $numEnt = 1;
        $this->addReference("entUser_$numEnt", $enterpriseUser);
        $enterpriseOne = new Enterprise();
        $enterpriseOne->setPaymentStatus(1);
        $enterpriseOne->setName("Enterprise OIne");
        $enterpriseOne->setLinkedinLink($faker->url);
        $manager->persist($enterpriseOne);
        $this->addReference("enterprise_$numEnt", $enterpriseOne);
        $enterpriseUser->setEnterprise($this->getReference("enterprise_$numEnt"));

        for ($l = 0; $l < self::NB_PROFILE_BY_ENTERPRISE; $l++) {
            $profile = new Profile();
            $profile->setPaymentType("All");
            $profile->setTitle($faker->jobTitle);
            $profile->setIsPropose(false);
            $profile->setIsRequest(true);
            $profile->setDateCreation($faker->dateTime);
            $this->addReference("profileEnt_$l", $profile);
            $manager->persist($profile);
            for ($k = 0; $k < rand(1, SkillFixtures::NB_MAX_SKIILS); $k++) {
                $profile->addSkill($this->getReference("skillNb_$k"));
            }
            $enterpriseOne->addProfile($this->getReference("profileEnt_$l"));
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SkillFixtures::class,];
    }
}
