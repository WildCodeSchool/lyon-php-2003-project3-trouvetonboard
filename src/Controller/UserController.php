<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Advisor;
use App\Repository\CategoryRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\CheckRoles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/user" ,  name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, User $user): Response
    {
        $formerMail = $user->getEmail();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $newMail = $user->getEmail();
            if ($formerMail !== $newMail) {
                $user->setIsVerified(false);
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/show", name="profile_show", methods={"GET","POST"})
     * @Route("/{id}}/show", name="show_id", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function profileShow(
        Request $request,
        CategoryRepository $categoryRepository,
        ProfileRepository $profileRepository,
        CheckRoles $checkRoles,
        ?User $userId
    ): Response {

        if ($checkRoles->check($request, "user_show_id")) {
            return $this->redirectToRoute("home");
        }

        $categories = $categoryRepository->findAll();
        $userRepo = $roles = $profile = $profileType = $user = $userType = "";


        if ($userId) {
            $user = $logUser = $userId;
            $userType = $user->getType();
        } else {
            $userRepo= $this->getDoctrine()->getManager()->getRepository(User::class);
            $logUser = $this->getUser();
        }


        if ($logUser) {
            $roles = $logUser->getRoles();
        }
        if (array_search("ROLE_ADVISOR", $roles)) {
            if ($logUser) {
                $profileType = $logUser->getAdvisor();
            }
            $profile = $profileType->getProfiles()[0];
        }
        $email = "";
        if (isset($logUser)) {
            $email = $logUser->getUsername();
        }
        if (!$userId) {
            $user = $logUser;
            if (method_exists($userRepo, "findOneBy")) {
                $user = $userRepo->findOneBy(["email" => $email]);
            }
        }


        return $this->render('user/profileShow.html.twig', [
            'user' => $user,
            'categories' => $categories,
            'profile' => $profile,
            'userType' => $userType
        ]);
    }

    /**
     * @Route("/profile/{id<[0-9]{1,}>}", name="profile_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function profileEdit(Request $request, User $user): Response
    {
        $logUser = $this->getUser();
        $iDLogUser = $logUser ? $logUser->getId() : 0;
        $msg = "Accès refusé, tentative d'accès à un emplacement non autorisé";

        try {
            if ($iDLogUser != $user->getId()) {
                throw new AccessDeniedException($msg);
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", $msg);
            return $this->redirectToRoute('user_profile_show');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
