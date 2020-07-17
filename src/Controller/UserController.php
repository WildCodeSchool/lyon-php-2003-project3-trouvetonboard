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

/**
 * @Route("/user" ,  name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.php', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(User $user, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $profile = null;
        $roles = $user->getRoles();
        if (array_search("ROLE_ADVISOR", $roles)) {
            $advisor = $user->getAdvisor();
            $profile = ($advisor)? $advisor->getProfiles()[0]: null;
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'categories' => $categories,
            'profile' => $profile
        ]);
    }

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
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
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
        $currentUser = $this->getUser();
        $currentUserId = ($currentUser)? $currentUser->getId(): null;
        $userId = $user->getId();
        if ($userId !== $currentUserId) {
            $this->addFlash("danger", "Vous n'avez pas les accès à cette route");
            return $this->redirectToRoute("home");
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
