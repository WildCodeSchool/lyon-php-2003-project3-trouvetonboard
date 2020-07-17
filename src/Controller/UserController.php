<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Advisor;
use App\Repository\CategoryRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
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

    // todo  delete at end debug , no use
//    /**
//     * @Route("/", name="index", methods={"GET"})
//     * @IsGranted("ROLE_ADMIN")
//     */
//    public function index(UserRepository $userRepository): Response
//    {
//        return $this->render('user/index.php', [
//            'users' => $userRepository->findAll(),
//        ]);
//    }
//
//    /**
//     * @Route("/new", name="new", methods={"GET","POST"})
//     * @IsGranted("ROLE_ADMIN")
//     */
//    public function new(Request $request): Response
//    {
//        $user = new User();
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();
//            return $this->redirectToRoute('user_index');
//        }
//
//        return $this->render('user/new.html.twig', [
//            'user' => $user,
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/{id}", name="show", methods={"GET"})
//     * @IsGranted("ROLE_ADMIN")
//     */
//    public function show(User $user, CategoryRepository $categoryRepository): Response
//    {
//        $categories = $categoryRepository->findAll();
//        $profile = null;
//        $roles = $user->getRoles();
//        if (array_search("ROLE_ADVISOR", $roles)) {
//            $advisor = $user->getAdvisor();
//            $profile = ($advisor)? $advisor->getProfiles()[0]: null;
//        }
//        return $this->render('user/show.html.twig', [
//            'user' => $user,
//            'categories' => $categories,
//            'profile' => $profile
//        ]);
//    }

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


// todo  delete at end debug , no use

//    /**
//     * @Route("/{id}", name="delete", methods={"DELETE"})
//     * @IsGranted("ROLE_ADMIN")
//     */
//    public function delete(Request $request, User $user): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($user);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('user_index');
//    }

    /**
     * @Route("/profile/show", name="profile_show", methods={"GET","POST"})
     * @Route("/{id}}/show", name="show_id", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function profileShow(
        Request $request,
        CategoryRepository $categoryRepository,
        ProfileRepository $profileRepository,
        ?User $userId
    ): Response {
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
