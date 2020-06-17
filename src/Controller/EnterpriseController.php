<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Entity\User;
use App\Form\EnterpriseType;
use App\Repository\EnterpriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/enterprise")
 */
class EnterpriseController extends AbstractController
{
    /**
     * @Route("/", name="enterprise_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(EnterpriseRepository $enterpriseRepository): Response
    {
        return $this->render('enterprise/index.html.twig', [
            'enterprises' => $enterpriseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="enterprise_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ENTERPRISE")
     */
    public function new(Request $request): Response
    {
        $enterprise = new Enterprise();
        $form = $this->createForm(EnterpriseType::class, $enterprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enterprise);
            $entityManager->flush();

            return $this->redirectToRoute('enterprise_index');
        }

        return $this->render('enterprise/new.html.twig', [
            'enterprise' => $enterprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enterprise_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Enterprise $enterprise): Response
    {


        $connectedEnterprise = ($user = $this->getUser()) ? $connectedUser->getEnterprise() : null;

        try {
            if (!$connectedEnterprise || ($connectedEnterprise->getId() != $enterprise->getId())) {
                throw new AccessDeniedException("Accès non autorisé - 
                Votre profil ne vous permet pas d'accéder à cette page");
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            return $this->redirectToRoute('home');
        }

        return $this->render('enterprise/show.html.twig', [
            'enterprise' => $enterprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="enterprise_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ENTERPRISE")
     */
    public function edit(Request $request, Enterprise $enterprise): Response
    {
        $connectedUser = $this->getUser();
        $connectedEnterprise = $connectedUser ? $connectedUser->getEnterprise() : null;

        try {
            if ($connectedEnterprise->getId() != $enterprise->getId()) {
                throw new AccessDeniedException("Acces refusé, tentative d'accés a un emplacement non autorisé");
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(EnterpriseType::class, $enterprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('enterprise_index');
        }

        return $this->render('enterprise/edit.html.twig', [
            'enterprise' => $enterprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enterprise_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Enterprise $enterprise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enterprise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($enterprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('enterprise_index');
    }
}
