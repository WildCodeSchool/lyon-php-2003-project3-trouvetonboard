<?php

namespace App\Controller;

use App\Entity\Advisor;
use App\Entity\User;
use App\Form\AdvisorType;
use App\Repository\AdvisorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/advisor")
 */
class AdvisorController extends AbstractController
{
    /**
     * @Route("/", name="advisor_index", methods={"GET"})
     */
    public function index(AdvisorRepository $advisorRepository): Response
    {
        return $this->render('advisor/index.php', [
            'advisors' => $advisorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="advisor_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $advisor = new Advisor();
        $form = $this->createForm(AdvisorType::class, $advisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($advisor);
            $entityManager->flush();

            return $this->redirectToRoute('advisor_index');
        }

        return $this->render('advisor/new.html.twig', [
            'advisor' => $advisor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="advisor_show", methods={"GET"})
     */
    public function show(Advisor $advisor): Response
    {
        return $this->render('advisor/show.html.twig', [
            'advisor' => $advisor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="advisor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Advisor $advisor): Response
    {
        $form = $this->createForm(AdvisorType::class, $advisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('advisor/edit.html.twig', [
            'advisor' => $advisor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="advisor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Advisor $advisor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advisor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($advisor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('advisor_index');
    }

    /**
     * @Route("/{id}/payment/{status}", name="advisor_payment_status")
     * @IsGranted("ROLE_ADMIN")
     */
    public function changePaymentStatus(
        Advisor $advisor,
        EntityManagerInterface $entityManager,
        int $status = 0
    ) :Response {

        $advisor->setPaymentStatus($status);
        $entityManager->persist($advisor);
        $entityManager->flush();

        return new Response("Ok");
    }
}
