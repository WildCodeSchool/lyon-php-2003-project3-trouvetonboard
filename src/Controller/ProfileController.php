<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Profile;
use App\Entity\Skill;
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
     * @Route("/newbdReq", name="newBdReq", methods={"GET","POST"})
     */
    public function newBdReq(
        Request $request,
        CategoryRepository $categoryRepository,
        SkillRepository $skillRepository
    ): Response {
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
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
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/newBoardRequest.html.twig', [
            'skillsByCategories' => $skillsByCategory,
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
     * @Route("/{id}/addSkill", name="addSkill", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function addSkill(Request $request, Profile $profile, Skill $skill, EntityManagerInterface $manager):
    Response
    {


        return $this->redirectToRoute('profile_index');
    }
}
