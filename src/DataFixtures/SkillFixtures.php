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
    const NB_MAX_SKIILS = 80;


    public function load(ObjectManager $manager)
    {
        $values = [
            "Type de mission" =>
                $this::getDataTypeDeMission(),
            "Rémunération" =>
                $this::getDataRemuneration(),
            "Taille de l'entreprise" =>
                $this::getDataTailleDeLentreprise(),
            "Type de l'entreprise" =>
                $this::getDataTypeDeLentreprise(),
            "Secteurs d'activité" =>
                $this::getDataSecteurActivite(),
            "Langues" =>
                $this::getDataLangue(),
            "Zones géographiques" =>
                $this::getDataZoneGeo(),
            "Expériences" =>
                $this::getDataExprerience(),
            "Intêrets" =>
                [
                    "enterpriseQuestion=Si ce n’est un advisor, que recherchez-vous ?",
                    "advisorQuestion=Quelles autres missions pourraient vous intéresser ?",
                    "Président de comité d'audit",
                    "Président de Conseil Administration",
                    "Administrateur indépendant",
                    "Président de comité de rémunération",
                ],
            "Missions de conseil" =>
                [
                    "enterpriseQuestion=Pour quelle mission recherchez-vous un consultant ?",
                    "advisorQuestion=Quelles missions recherchez-vous ?",
                    "Développement Durable",
                    "R&D",
                    "Risque et conformité",
                    "Marketing",
                    "Juridique",
                    "M&A",
                    "Ressources humaines",
                    "Production",
                    "Finance",
                    "Digital et technologie",
                    "Président de comité de rémunération",
                    "Communication",
                    "Export",
                    "Commercial / vente"
                ],
        ];
        // Création du  level 1
        $cat = 0;
        $skillNb = 0;
        foreach ($values as $key => $categorys) {
            $category = new Category();
            $category->setName($key);
            $manager->persist($category);
            $this->addReference("category_" . $cat, $category);
            for ($a = 0; $a < count($categorys); $a++) {
                $search = 'enterpriseQuestion=';
                $search2 = "advisorQuestion=";
                if (preg_match("/{$search}/i", $categorys[$a])) {
                    $category->setEnterpriseQuestion(str_replace($search, "", $categorys[$a]));
                } elseif (preg_match("/{$search2}/i", $categorys[$a])) {
                    $category->setAdvisorQuestion(str_replace($search2, "", $categorys[$a]));
                } else {
                        $skill = new Skill();
                        $skill->setName($categorys[$a]);
                        $skill->setCategory($this->getReference("category_" . $cat));
                        $this->addReference("skill_" . $cat . "_" . $a, $skill);
                        $this->addReference("skillNb_$skillNb", $skill);
                        $skillNb++;
                        $manager->persist($skill);
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

    private static function getDataTypeDeMission() : array
    {
        return [
            "enterpriseQuestion=Quel type de mission d’advisor proposez-vous ?",
            "advisorQuestion=Vous recherchez des missions d’advisor :",
            "Payantes",
            "Bénévoles",
            "Bénévoles puis payantes"
        ];
    }

    private static function getDataRemuneration() : array
    {
        return [
            "enterpriseQuestion=Comment souhaitez-vous rémunérer votre advisor ?",
            "advisorQuestion=Comment souhaitez-vous être rémunéré pour votre mission d’advisor ?",
            "Salaire",
            "Défraiement",
            "Equity"
        ];
    }

    private static function getDataTailleDeLentreprise(): array
    {
        return [
            "enterpriseQuestion=Vous recherchez un advisor pour votre entreprise 
            ou pour un nouveau projet au stade de :",
            "advisorQuestion=Quel type de projet / d’entreprise souhaitez-vous accompagner ?",
            "Idéation",
            "Start-up",
            "TPE",
            "PME",
            "ETI",
            "Grand groupe"
        ];
    }

    private static function getDataTypeDeLentreprise() : array
    {
        return [
            "enterpriseQuestion=L’advisor que vous souhaitez recruter doit connaitre les environnements suivants :",
            "advisorQuestion=Vous avez travaillé dans quel type d’entreprise pendant votre carrière ?",
            "Start-up",
            "Société cotée",
            "Entreprise familiale",
            "Entreprise publique",
            "Association",
            "Autre"
        ];
    }

    private static function getDataSecteurActivite(): array
    {
        return [
            "enterpriseQuestion=L’advisor que vous souhaitez recruter doit avoir travaillé 
             dans les secteurs suivants :",
            "advisorQuestion=Dans quels secteurs avez-vous travaillé pendant votre carrière ?",
            "Biens de consommation",
            "Éducation",
            "Énergie et ressources naturelles",
            "Finance",
            "Organisme public",
            "Santé",
            "Industrie",
            "Médias",
            "ONG / Associations",
            "Services à la personne",
            "Technologie / Internet",
            "Immobilier",
            "Télécommunications",
            "Autre"
        ];
    }

    public static function getDataLangue() : array
    {
        return [
            "enterpriseQuestion=Quelles compétences linguistiques sont nécessaires pour vous accompagner ?",
            "advisorQuestion=Pour vous, les langues suivantes sont des langues de travail ? :",
            "Français",
            "Anglais",
            "Allemand",
            "Espagnol",
            "Italien",
            "Arabe",
            "Cantonais",
            "Danois",
            "Néerlandais",
            "Finlandais",
            "Grec",
            "Hindi",
            "Japonais",
            "Mandarin",
            "Norvégien",
            "Portugais",
            "Russe",
            "Suédois",
            "Taiwanais",
        ];
    }

    public static function getDataZoneGeo()
    {
        return [
            "enterpriseQuestion=De quelles zones géographiques votre advisor doit-il être expert ?",
            "advisorQuestion=Vous êtes expert des zones géographiques suivantes :",
            "Europe",
            "Asie",
            "U.S. / Canada",
            "Afrique",
            "Amérique Sud / Centrale",
            "Russie",
            "Australie",
            "Moyen Orient",
        ];
    }

    public static function getDataExprerience()
    {
        return [
            "enterpriseQuestion=L’advisor que vous souhaitez recruter doit maitriser les expertises suivantes :",
            "advisorQuestion=Quelles sont vos expertises métier justifiées par des expériences / missions réussies ?",
            "Finance",
            "Business Développement",
            "Transformation / gestion du changement",
            "Système d’information(Infra, Run, ERP…)",
            "Technologie / Digitale",
            "HR",
            "Achats",
            "Juridique / conformité",
            "R & D /, Innovation",
            "Stratégie / Organisation",
            "Production / industrialisation",
            "Supply / Logistique",
            "Export",
            "Commerce",
            "Communication / Marketing",
        ];
    }
}
