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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MatchsController extends AbstractController
{
    /**
     * @Route("/matchs", name="matchs_index")
     */
    public function index(AdvisorRepository $advisorRepository, ProfileRepository $profileRepository): Response
    {
        return $this->render('matchs/index.html.twig');
    }

    /**
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/enterprise", name="match_board_request_one")
     */
    public function matchsFirstAdvisorByBoardRequest(ProfileRepository $profileRepository)
    {
        $boardRequestOne = $matchsBordRequestOne = $idEnterprise = null;
        $bordRequestOneId = 0;

        $logUser = $this->getUser();
        if ($logUser) {
            $idEnterprise = $logUser->getEnterprise()->getId();
        }
        $boardRequests = $profileRepository->findBy(
            [
                "archived" => false,
                "enterprise" => $idEnterprise
            ],
            [
                "dateCreation" => "DESC"
            ]
        );

        if (!empty($boardRequests)) {
            $boardRequestOne = $boardRequests[0];
            $bordRequestOneId = $boardRequestOne->getId();
            $matchsBordRequestOne = $profileRepository->findAdvisorMatchsByBoardRequest($bordRequestOneId);
        }

        return $this->render(
            'matchs/matchsBoardRequest.html.twig',
            [
                'boardRequests' => $boardRequests,
                'matchs' => $matchsBordRequestOne
            ]
        );
    }

    /**
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/enterprise/{id<[0-9]{1,}>}", name="match_board_request_enterprise")
     */
    public function matchsAdvisorByBoardRequest(Profile $bordRequest, ProfileRepository $profileRepository)
    {
        $boardRequestOne = $matchsBordRequestOne = $idEnterprise = null;
        $bordRequestOneId = 0;

        $logUser = $this->getUser();
        if ($logUser) {
            $idEnterprise = $logUser->getEnterprise()->getId();
        }
        $boardRequests = $profileRepository->findBy(
            [
                "archived" => false,
                "enterprise" => $idEnterprise
            ],
            [
                "dateCreation" => "DESC"
            ]
        );

        if (!empty($boardRequests)) {
            $boardRequestOne = $boardRequests[0];
            $bordRequestOneId = $boardRequestOne->getId();
            $matchsBordRequestOne = $profileRepository->findAdvisorMatchsByBoardRequest($bordRequest->getId());
        }

        return $this->render(
            'matchs/matchsBoardRequest.html.twig',
            [
                'boardRequests' => $boardRequests,
                'matchs' => $matchsBordRequestOne
            ]
        );
    }


    /**
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/advisor", name="match_advisor_boardRequest")
     * @IsGranted("ROLE_ADVISOR")
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
     * @IsGranted("ROLE_ADVISOR")
     */
    public function matchDetails(Profile $aProfile, Profile $eProfile)
    {
        $idLogProfileAdvisor = null;
        $logUser = $this->getUser();
        if ($logUser) {
            $idLogProfileAdvisor = $logUser->getAdvisor()->getProfiles()[0]->getId();
        }

        try {
            if ($idLogProfileAdvisor != $aProfile->getId()) {
                throw new AccessDeniedException("Acces refusé, tentative d'accés a un emplacement non autorisé");
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", "Acces refusé, tentative d'accés a un emplacement non autorisé");
            return $this->redirectToRoute('match_advisor_boardRequest');
        }

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
     * @Route("/matchs/enterprise/{aProfile<[0-9]{1,}>}/{eProfile<[0-9]{1,}>}", name="match_details_enterprise")
     * @ParamConverter("aProfile", options={"id" = "aProfile"})
     * @ParamConverter("eProfile", options={"id" = "eProfile"})
     * @IsGranted("ROLE_ENTERPRISE")
     */
    public function matchDetailsEnterprise(Profile $aProfile, Profile $eProfile)
    {
        // todo secure access by  other logged enterprise

        return $this->render(
            'matchs/matchDetailsEnterprise.html.twig',
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
     * @IsGranted("ROLE_ADVISOR")
     */
    public function matchRequestEmail(Profile $aProfile, Profile $eProfile, MailerInterface $mailer)
    {
        $idLogProfileAdvisor = null;
        $logUser = $this->getUser();
        if ($logUser) {
            $idLogProfileAdvisor = $logUser->getAdvisor()->getProfiles()[0]->getId();
        }

        try {
            if ($idLogProfileAdvisor != $aProfile->getId()) {
                throw new AccessDeniedException("Acces refusé, tentative d'accés a un emplacement non autorisé");
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", "Acces refusé, tentative d'accés a un emplacement non autorisé");
            return $this->redirectToRoute('match_advisor_boardRequest');
        }

        //$advisorEmail = $aProfile->getAdvisor()->getUser()->getEmail();
        $email = (new TemplatedEmail())
            ->from($this->getParameter("mailer_from"))
            ->to($this->getParameter("mailer_cedric"))
            ->subject('Nouvelle demande de mise en relation')
            // path of the Twig template to render
            ->htmlTemplate('matchs/matchRequestEmail.html.twig')
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
