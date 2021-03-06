<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Skill;
use App\Repository\AdvisorRepository;
use App\Repository\ProfileRepository;
use App\Repository\SkillRepository;
use App\Service\CheckRoles;
use App\Service\MatchArraySort;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class MatchsController
 *
 * @package App\Controller
 */
class MatchsController extends AbstractController
{

    /**
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/enterprise", name="match_board_request_one")
     * @Route("/matchs/enterprise/{id<[0-9]{1,}>}", name="match_board_request_enterprise")
     * @IsGranted({"ROLE_ENTERPRISE","ROLE_ADMIN"})
     */
    public function matchsAdvisorByBoardRequest(?Profile $boardRequest, ProfileRepository $profileRepository)
    {
        $boardRequestId = $matchsBoardRequest  = null;
        $logUser = $this->getUser();
        $roles = $logUser ? $logUser->getRoles() : [];
        if ($logUser && !in_array("ROLE_ADMIN", $roles)) {
            if ($boardRequest) {
                if (!$logUser->getEnterprise()->getProfiles()->contains($boardRequest)) {
                    $this->addFlash(
                        "danger",
                        "Accès refusé, tentative d'accès à un emplacement non autorisé"
                    );

                    return $this->redirectToRoute('match_board_request_one');
                }
            }
            $enterprise = $logUser->getEnterprise();
        } else {
            $enterprise = $boardRequest ? $boardRequest->getEnterprise() : null;
        }
        $boardRequests = $profileRepository->findBy(
            [
                "archived" => false,
                "enterprise" => $enterprise
            ],
            [
                "dateCreation" => "DESC"
            ]
        );

        if (!empty($boardRequests)) {
            $boardRequestId = $boardRequest ? $boardRequest->getId() : $boardRequests[0]->getId();
            $matchsBoardRequest = $profileRepository->findAdvisorMatchsByBoardRequest($boardRequestId);
        }

        return $this->render(
            'matchs/matchsBoardRequestEnterprise.html.twig',
            [
                'boardRequests' => $boardRequests,
                'matchs' => $matchsBoardRequest,
                'activeBoardRequestId' => $boardRequestId,
            ]
        );
    }


    /**
     * @param ProfileRepository $profileRepository
     *
     * @return Response
     * @Route("/matchs/advisor", name="match_advisor_boardRequest")
     * @Route("/{id}/matchs/advisor", name="match_advisor_id")
     * @IsGranted({"ROLE_ADVISOR" , "ROLE_ADMIN"})
     */
    public function matchsEnterpriseByAdvisor(
        Request $request,
        ProfileRepository $profileRepository,
        CheckRoles $checkRoles,
        ?Profile $profile
    ) {
        if ($checkRoles->check($request, "match_advisor_id")) {
            return $this->redirectToRoute("home");
        }

        $idProfileAdvisor = null;
        $getUser = $this->getUser();
        $getRoles = ($getUser) ? $getUser->getRoles() : null;

        if (in_array("ROLE_ADMIN", $getRoles)) {
            $idProfileAdvisor = ($profile) ? $profile->getId() : null;
        } else {
            $logUser = $this->getUser();
            if ($logUser) {
                $idProfileAdvisor = $logUser->getAdvisor()->getProfiles()[0]->getId();
                // used for calculate % match in bordRequestMatch match for enterprise
            }
        }

        // newMatchs exsist for pronlem to calculate % in advisor match
        $newMatch = [];
        $matchs = $profileRepository->findEnterpriseMatchsByAdvisor($idProfileAdvisor);
        for ($i = 0; $i < count($matchs); $i++) {
            $match = $matchs[$i];
            $eProfile = $profileRepository->findOneBy(["id" => $match["board_request_id"]]);
            $total = $eProfile ? count($eProfile->getSkills()) : 0;
            $match["TOTAL"] = $eProfile ? count($eProfile->getSkills()) : 0;
            $match["percent"] = ($match["SCORE"] / $total) * 100;
            $newMatch[] = $match;
        }

        $sorter = new MatchArraySort();

        return $this->render(
            'matchs/matchAdvisorBoardRequest.html.twig',
            [
                'matchs' => $sorter->arraySort($newMatch, 'percent', SORT_DESC),
                //'nbSkillAdvisor' => $nbSkillAdvisor,
            ]
        );
    }


    /**
     * @param \App\Entity\Profile $eProfile Enterprise profile
     * @param \App\Entity\Profile $aProfile Advisor profile
     *
     * @return Response
     * @Route("/matchs/{aProfile<[0-9]{1,}>}/{eProfile<[0-9]{1,}>}", name="match_details")
     * @ParamConverter("aProfile", options={"id" = "aProfile"})
     * @ParamConverter("eProfile", options={"id" = "eProfile"})
     * @IsGranted({"ROLE_ADVISOR" , "ROLE_ADMIN"})
     */
    public function matchDetails(Profile $aProfile, Profile $eProfile)
    {
        $idLogProfileAdvisor = null;
        $getUser = $this->getUser();
        $getRoles = ($getUser) ? $getUser->getRoles() : null;

        if (in_array("ROLE_ADMIN", $getRoles)) {
            $idLogProfileAdvisor = $aProfile->getId();
        } else {
            $logUser = $this->getUser();
            if ($logUser) {
                $idLogProfileAdvisor = $logUser->getAdvisor()->getProfiles()[0]->getId();
            }
        }

        try {
            if ($idLogProfileAdvisor != $aProfile->getId()) {
                throw new AccessDeniedException("Accès refusé, tentative d'accès à un emplacement non autorisé");
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", "Accès refusé, tentative d'accès à un emplacement non autorisé");
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
     * @IsGranted({"ROLE_ENTERPRISE", "ROLE_ADMIN"})
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
     * @IsGranted({"ROLE_ENTERPRISE","ROLE_ADVISOR","ROLE_ADMIN"})
     */
    public function matchRequestEmail(Profile $aProfile, Profile $eProfile, MailerInterface $mailer)
    {
        $idLoggedUser = $idUserProfile = $roles = null;
        $templatePath = "";
        $route = "home";
        $logUser = $this->getUser();
        if ($logUser) {
            $roles = $logUser->getRoles();
            $idLoggedUser = $logUser->getId();
            if (in_array('ROLE_ENTERPRISE', $roles)) {
                $templatePath = 'matchs/matchRequestEmailEnterprise.html.twig';
                $route = "match_board_request_one";
                $enterprise = $eProfile->getEnterprise();
                $idUserProfile = $enterprise ? $enterprise->getUsers()[0]->getId() : 0;
            } else { // else is for role advisor + admin , if  admin only route change for send email match
                $templatePath = 'matchs/matchRequestEmail.html.twig';
                $route = in_array('ROLE_ADMIN', $logUser->getRoles()) ? "admin_users" : "match_advisor_boardRequest";
                $advisor = $aProfile->getAdvisor();
                $advisorUser = $advisor ? $advisor->getUser() : 0;
                $idUserProfile = $advisorUser ? $advisorUser->getId() : 0;
            }
        }

        if ($idLoggedUser != $idUserProfile && !(in_array('ROLE_ADMIN', $roles))) {
            $this->addFlash("danger", "Accès refusé, tentative d'accès à un emplacement non autorisé");
            return $this->redirectToRoute($route);
        }

        $email = (new TemplatedEmail())
            ->from($this->getParameter("mailer_from"))
            ->to($this->getParameter("mailer_cedric"))
            ->subject('Nouvelle demande de mise en relation')
            // path of the Twig template to render
            ->htmlTemplate($templatePath)
            // pass variables (name => value) to the template
            ->context([
                'date' => new DateTime('now'),
                "eProfile" => $eProfile,
                "aProfile" => $aProfile,
            ]);

        $mailer->send($email);
        $this->addFlash('success', "Un email de demande de mise en relation a été envoyé 
        à l'administrateur. Vous serez recontacté dans les plus brefs délais.");

        return $this->redirectToRoute($route);
    }

    /**
     * @param \App\Entity\Profile $eProfile Enterprise profile
     *
     * @return Response
     * @Route("/matchs/paymentemail/{id<[0-9]{1,}>}", name="match_request_payment_email")
     */
    public function matchRequestPaymentEmail(Profile $eProfile, MailerInterface $mailer)
    {

        $logUser = $this->getUser();
        $email = (new TemplatedEmail())
            ->from($this->getParameter("mailer_from"))
            ->to($this->getParameter("mailer_cedric"))
            ->subject('Nouvelle demande de mise en relation')
            // path of the Twig template to render
            ->htmlTemplate("matchs/matchRequestPaymentEmail.hmtl.twig")
            // pass variables (name => value) to the template
            ->context([
                'date' => new DateTime('now'),
                "user" => $logUser,
            ]);

        $mailer->send($email);
        $this->addFlash('success', "Un email de demande de contact a été envoyé 
        à l'administrateur. Vous serez recontacté dans les plus brefs delais.");

        return $this->redirectToRoute("match_board_request_enterprise", ["id" => $eProfile->getId()]);
    }
}
