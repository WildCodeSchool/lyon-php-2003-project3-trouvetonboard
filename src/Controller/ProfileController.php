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
        return $this->render('profile/index.html.twig', [
            'profiles' => $profileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newBoardRequest", name="newBoardRequest", methods={"GET","POST"})
     */
    public function newBoardRequest(
        Request $request,
        CategoryRepository $categoryRepository,
        SkillRepository $skillRepository
    ): Response {

        // persit object immediatly  because skill choice are updated with ajax // todo after creation  , not posible
        // to cancel or it will be take in consideration
        $boardRequest = new Profile();
        $boardRequest->setIsRequest(true);
        $boardRequest->setIsPropose(false);
        $boardRequest->setPaymentType("Vide");
        $boardRequest->setTitle("Please chose a title");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($boardRequest);
        $entityManager->flush();
        $boardRequestId = $boardRequest->getId();
        $form = $this->createForm(ProfileType::class, $boardRequest);
        $form->handleRequest($request);

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
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($boardRequest);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/newBoardRequest.html.twig', [
            'skillsByCategories' => $skillsByCategory,
            "profileId" => $boardRequestId
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

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/new.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Profile $profile): Response
    {
        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Profile $profile): Response
    {
        if ($this->isCsrfTokenValid('delete' . $profile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile_index');
    }

    /**
     * @Route("/{id}/addSkill/{skillId}", name="addSkill")
     * @IsGranted("ROLE_USER")
     * @ParamConverter("profile", options={"id" = "id"})
     * @ParamConverter("skill", options={"id" = "skillId"})
     */
    public function addSkill(Profile $profile, Skill $skill, EntityManagerInterface $manager)
    {
        // todo add advisor or enterprise detection
        //$connectedUser = new User();
        /*
        if($connectedUser->getEnterprise()) {
            $profiles = $connectedUser->getEnterprise()->getProfiles();
        }

        $connectedUser = $this->getUser();


        if ($connectedUser) {
           // recup profile id
            // recupt le skill id
            // affecter/desaffecter le skill  au  profile
            // return true or flase au  js .
        }
        return $this->redirectToRoute('profile_index');
        */
        return $this->json([
            'isChecked' => true
        ]);
    }

    /**
     * @Route("/{id}/removeSkill/{skillId}", name="removeSkill")
     * @IsGranted("ROLE_USER")
     * @ParamConverter("profile", options={"id" = "id"})
     * @ParamConverter("skill", options={"id" = "skillId"})
     */
    public function removeSkill(Profile $profile, Skill $skill, EntityManagerInterface $manager)
    {
        // todo add advisor or enterprise detection
        //$connectedUser = new User();
        /*
        if($connectedUser->getEnterprise()) {
            $profiles = $connectedUser->getEnterprise()->getProfiles();
        }

        $connectedUser = $this->getUser();


        if ($connectedUser) {
           // recup profile id
            // recupt le skill id
            // affecter/desaffecter le skill  au  profile
            // return true or flase au  js .
        }
        return $this->redirectToRoute('profile_index');
        */
        return $this->json([
            'isChecked' => false
        ]);
    }
}
