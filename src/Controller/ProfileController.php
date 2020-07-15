<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Profile;
use App\Entity\Skill;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\CategoryRepository;
use App\Repository\ProfileRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use \DateTime;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/profile" , name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ProfileRepository $profileRepository): Response
    {
        return $this->render('profile/index.php', [
            'profiles' => $profileRepository->findAll(),
        ]);
    }


    /**
     * @Route("/editSkillBoardRequest/{id}", name="editSkillBoardRequest", methods={"GET","POST"})
     */
    public function editSkillBoardRequest(
        Profile $boardRequest,
        CategoryRepository $categoryRepository,
        SkillRepository $skillRepository
    ) : Response {
        // get the skill type list.
        $categories = $categoryRepository->findAll();

        // construct a skill block
        $skillsByCategory = [];
        foreach ($categories as $category) {
            $skills = $skillRepository->findByCategory($category->getId());
            $skillsByCategory[] = [
                "category" => $category,
                "skills" => $skills,
            ];
        }

        return $this->render('profile/editBordRequestEnterprise.html.twig', [
            'skillsByCategories' => $skillsByCategory,
            "profile" => $boardRequest
        ]);
    }


    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        // code for travis control refused because object or null returned

        $enterprise = null;
        $user = $this->getUser();
        if ($user) {
            $enterprise = $user->getEnterprise();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $profile->setIsRequest(true);
            $profile->setIsPropose(false);
            $profile->setPaymentType("Vide");
            $profile->setDateCreation(new DateTime("now"));
            $profile->setEnterprise($enterprise);
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('profile_editSkillBoardRequest', ["id" => $profile->getId()]);
        }

        return $this->render('profile/new.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @IsGranted({"ROLE_ENTERPRISE","ROLE_ADVISOR"})
     */
    public function edit(Request $request, Profile $profile): Response
    {
        $logUser = $this->getUser();
        $roles = $logUser ? $logUser->getRoles() : null;
        $msg = "Accès refusé, tentative d'accès à un emplacement non autorisé";
        $method = in_array('ROLE_ENTERPRISE', $roles) ? "Enterprise" : "Advisor";

        try {
            if ($logUser->{"get" . $method}()->getId() != $profile->{"get" . $method}()->getId()) {
                throw new AccessDeniedException($msg);
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", $msg);
            return $this->redirectToRoute('home');
        }


        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/archive/{id}", name="archive", methods={"GET"})
     * @IsGranted("ROLE_ENTERPRISE")
     */
    public function archive(Profile $profile): Response
    {

        $logUser = $this->getUser();
        $msg = "Accès refusé, tentative d'accès à un emplacement non autorisé";
        try {
            $enterprise = $logUser ? $logUser->getEnterprise() : null;
            if (!$enterprise) {
                throw new AccessDeniedException($msg);
            }
            if ($enterprise->getId() != $enterprise->getId()) {
                throw new AccessDeniedException($msg);
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", $msg);
            return $this->redirectToRoute('user_profile_show');
        }


        $entityManager = $this->getDoctrine()->getManager();

        $profile->setArchived(true);
        $entityManager->flush();
        $this->addFlash(
            "warning",
            "Le profil a été archivé, il ne sera plus affiché dans les recherches des Advisors."
        );


        return $this->redirectToRoute('user_profile_show');
    }

    /**
     * @Route("/restore/{id}", name="restore", methods={"GET"})
     * @IsGranted("ROLE_ENTERPRISE")
     */
    public function restore(Profile $profile): Response
    {
        $logUser = $this->getUser();
        $msg = "Accès refusé, tentative d'accès à un emplacement non autorisé";
        try {
            $enterprise = $logUser ? $logUser->getEnterprise() : null;
            if (!$enterprise) {
                throw new AccessDeniedException($msg);
            }
            if ($enterprise->getId() != $enterprise->getId()) {
                throw new AccessDeniedException($msg);
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            $this->addFlash("danger", $msg);
            return $this->redirectToRoute('user_profile_show');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $profile->setArchived(false);
        $entityManager->flush();
        $this->addFlash(
            "success",
            "Le profil a été restauré, il est à nouveau proposé dans les recherches des Advisors."
        );

        return $this->redirectToRoute('user_profile_show');
    }

//    /**
//     * @Route("/{id}", name="delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, Profile $profile): Response
//    {
//        if ($this->isCsrfTokenValid('delete' . $profile->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($profile);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('profile_index');
//    }

    /**
     * @Route("/{id}/addSkill/{skillId}", name="addSkill")
     * @IsGranted("ROLE_USER")
     * @ParamConverter("profile", options={"id" = "id"})
     * @ParamConverter("skill", options={"id" = "skillId"})
     */
    public function addSkill(Request $request, Profile $profile, Skill $skill, EntityManagerInterface $manager)
    {
        $token = $request->request->get("token");
        // check if token is valid

        if ($this->isCsrfTokenValid('requestToken', $token)) {
            // check if id give are given org valid user
            $logUser = $this->getUser();
            $roles = $logUser ? $logUser->getRoles() : null;
            $msg = "Accès refusé, tentative d'accès à un emplacement non autorisé";
            // admin cant change skills
            $method = in_array('ROLE_ENTERPRISE', $roles) ? "Enterprise" : "Advisor";
            try {
                $idTypeLoggedUser = $logUser->{"get" . $method}()->getId();
                $idTypeProfileUser = $profile->{"get" . $method}()->getId();
                if ($idTypeLoggedUser != $idTypeProfileUser) {
                    throw new AccessDeniedException($msg);
                }
            } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
                //$this->addFlash("danger", $msg);

                return $this->json([
                    'isChecked' => "Refused"
                ]);
            }

            $profile->addSkill($skill);
            $manager->persist($profile);
            $manager->flush();

            return $this->json([
                'isChecked' => true
            ]);
        }
    }

    /**
     * @Route("/{id}/removeSkill/{skillId}", name="removeSkill")
     * @IsGranted("ROLE_USER")
     * @ParamConverter("profile", options={"id" = "id"})
     * @ParamConverter("skill", options={"id" = "skillId"})
     */
    public function removeSkill(Request $request, Profile $profile, Skill $skill, EntityManagerInterface $manager)
    {
        $token = $request->request->get("token");
        if ($this->isCsrfTokenValid('requestToken', $token)) {
            // check if id give are given org valid user
            $logUser = $this->getUser();
            $roles = $logUser ? $logUser->getRoles() : null;
            $msg = "Accès refusé, tentative d'accès à un emplacement non autorisé";
            // admin cant change skills result , only enterprise or advisor possible
            $method = in_array('ROLE_ENTERPRISE', $roles) ? "Enterprise" : "Advisor";
            try {
                $idTypeLoggedUser = $logUser->{"get" . $method}()->getId();
                $idTypeProfileUser = $profile->{"get" . $method}()->getId();
                if ($idTypeLoggedUser != $idTypeProfileUser) {
                    throw new AccessDeniedException($msg);
                }
            } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
                return $this->json([
                    'isChecked' => "Refused"
                ]);
            }
            $profile->removeSkill($skill);
            $manager->persist($profile);
            $manager->flush();

            return $this->json([
                'isChecked' => false
            ]);
        }
    }

    /**
     * @Route("/{id}/checkAllSkill", name="checkAllSkill")
     * @IsGranted({"ROLE_USER"})
     */
    public function checkAllSkills(Request $request, Profile $profile)
    {

        $token = $request->request->get("token");
        $skillsId = [];
        if ($this->isCsrfTokenValid('requestToken', $token)) {
            foreach ($profile->getSkills() as $skill) {
                $skillsId[] = $skill->getId();
            }

            return $this->json([
                'skillsId' => $skillsId
            ]);
        }
        $this->addFlash("danger", "Action non permise.");
        return $this->json([
            'skillsId' => $skillsId
        ]);
    }
}
