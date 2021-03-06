<?php

namespace App\Controller;

use App\Entity\Advisor;
use App\Entity\User;
use App\Form\AdvisorType;
use App\Kernel;
use App\Repository\AdvisorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Spatie\PdfToImage\Pdf;
use Imagick;

/**
 * @Route("/advisor")
 */
class AdvisorController extends AbstractController
{

    /**
     * @Route("/new", name="advisor_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
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
     * @Route("/{id<[0-9]{1,}>}/edit", name="advisor_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADVISOR")
     */
    public function edit(Request $request, Advisor $advisor, KernelInterface $kernel): Response
    {
        $form = $this->createForm(AdvisorType::class, $advisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($advisor->getCvLink() !== null) {
                $uniqueCV = explode('.', $advisor->getCvLink());

                $path = $this->getParameter("upload_advisors_cv_directory");
                $pdf = new Pdf($path . "/" . $advisor->getCvLink());
                $pdf->saveImage($path . '/' . $uniqueCV[0] . ".jpg");
            }

            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('advisor/edit.html.twig', [
            'advisor' => $advisor,
            'form' => $form->createView(),
        ]);
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
