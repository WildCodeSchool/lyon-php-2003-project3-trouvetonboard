<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Skill;
use App\Repository\AdvisorRepository;
use App\Repository\ProfileRepository;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchsController extends AbstractController
{
    /**
     * @Route("/matchs", name="matchs_index")
     */
    public function index(AdvisorRepository $advisorRepository, ProfileRepository $profileRepository) :Response
    {
//        $user = $this->getUser();
//        $enterprise = $user->getEnterprise();
//        $boardRequests = $enterprise->getProfiles();
//        $skillsBoardRequest = $boardRequests[0]->getSkills();
//
//        $advisors = $advisorRepository->findAll();
//
//
//        foreach ($advisors as $advisor) {
//            $skills = $advisor->getProfiles()[0]->getSkills();
//            $matchs = 0;
//            foreach ($skillsBoardRequest as $skill) {
//                if ($skills->contains($skill)) {
//                    $matchs++;
//                    $bdTitle = 'board request name: ' . $boardRequests[0]->getTitle();
//                    $adName = 'advisor name: ' . $advisor->getUser()->getFirstName();
//                    var_dump($bdTitle, $adName);
//                }
//            }
//            $br = '------------------------------------------------------';
//            var_dump("nombre de matchs: $matchs", $br);
//        }
        return $this->render('matchs/index.html.twig');
    }
}
