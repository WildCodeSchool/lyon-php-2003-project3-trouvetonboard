<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Skill;
use App\Repository\AdvisorRepository;
use App\Repository\ProfileRepository;
use App\Repository\SkillRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;

class MatchsController extends AbstractController
{
    /**
     * @Route("/matchs", name="matchs_index")
     */
    public function index(AdvisorRepository $advisorRepository, ProfileRepository $profileRepository): Response
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
//        $test = $profileRepository->findAdvisorMatchsByBoardRequest(31);
//        $query = $test->getQuery();
//        var_dump($query->getDQL());


        return $this->render('matchs/index.html.twig');
    }

    /**
     * @param int               $id
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/{id<[0-9]{1,}>}", name="match_boardrequest")
     */
    public function matchsAdvisorByBoardRequest(int $id, ProfileRepository $profileRepository)
    {
        $matchs = $profileRepository->findAdvisorMatchsByBoardRequest($id);
        return $this->render('matchs/matchsBoardRequest.html.twig', ['matchs' => $matchs]);
    }

    /**
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/advisor", name="match_advisor_boardRequest")
     */
    public function matchsEnterpriseByAdvisor(ProfileRepository $profileRepository)
    {
        $idProfileAdvisor = null;
        $logUser = $this->getUser();
        if ($logUser) {
            $idProfileAdvisor = $logUser->getAdvisor()->getProfiles()[0]->getId();
        }
        $matchs = $profileRepository->findEnterpriseMatchsByAdvisor($idProfileAdvisor);
        return $this->render('matchs/matchAdvisorBoardRequest.html.twig', ['matchs' => $matchs]);
    }

    /**
     * @param \App\Entity\Profile $eProfile Enterprise profile
     * @param \App\Entity\Profile $aProfile Advisor profile
     *
     * @return Response
     * @Route("/matchs/{aProfile<[0-9]{1,}>}/{eProfile<[0-9]{1,}>}", name="match_details")
     * @ParamConverter("aProfile", options={"id" = "aProfile"})
     * @ParamConverter("eProfile", options={"id" = "eProfile"})
     */
    public function matchDetails(Profile $aProfile, Profile $eProfile)
    {
        return $this->render(
            'matchs/matchDetails.html.twig',
            [
                'aProfile' => $aProfile,
                "eProfile" => $eProfile
            ]
        );
    }

    /**
     * @param \App\Entity\Profile $eProfile Enterprise profile
     * @param \App\Entity\Profile $aProfile Advisor profile
     *
     * @return Response
     * @Route("/matchs/requestemail/{aProfile<[0-9]{1,}>}/{eProfile<[0-9]{1,}>}", name="match_request_email")
     * @ParamConverter("aProfile", options={"id" = "aProfile"})
     * @ParamConverter("eProfile", options={"id" = "eProfile"})
     */
    public function matchRequestEmail(Profile $aProfile, Profile $eProfile, MailerInterface $mailer)
    {
        //$advisorEmail = $aProfile->getAdvisor()->getUser()->getEmail();
        $email = (new TemplatedEmail())
            ->from($this->getParameter("mailer_from"))
            ->to($this->getParameter("mailer_cedric"))
            ->subject('Nouvelle demande de mise en relation')
            // path of the Twig template to render
            ->htmlTemplate('matchs/match_request_email.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'date' => new DateTime('now'),
                "eProfile" => $eProfile,
                "aProfile" => $aProfile,
            ]);

        $mailer->send($email);
        $this->addFlash('success', "Un email de demande de mise en relation a été envoyé 
        a l'administrateur. Vous serez recontacter dans les plus bref delais.");

        return $this->redirectToRoute("match_advisor_boardRequest");
    }
}
