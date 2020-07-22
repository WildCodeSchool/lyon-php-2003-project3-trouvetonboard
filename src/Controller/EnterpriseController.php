<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Entity\User;
use App\Form\EnterpriseType;
use App\Repository\EnterpriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Spatie\PdfToImage\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/enterprise")
 */
class EnterpriseController extends AbstractController
{

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
            $user = $this->getUser();
            if ($user) {
                $user = $user->setEnterprise($enterprise);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('enterprise/new.html.twig', [
            'enterprise' => $enterprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<[0-9]{1,}>}", name="enterprise_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Enterprise $enterprise): Response
    {

        $connectedEnterprise = ($user = $this->getUser()) ? $user->getEnterprise() : null;
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
     * @Route("/{id<[0-9]{1,}>}/edit", name="enterprise_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ENTERPRISE")
     */
    public function edit(Request $request, Enterprise $enterprise, KernelInterface $kernel): Response
    {
        $connectedUser = $this->getUser();
        $connectedEnterprise = $connectedUser ? $connectedUser->getEnterprise() : null;

        try {
            if ($connectedEnterprise->getId() != $enterprise->getId()) {
                throw new AccessDeniedException("Accès refusé, tentative d'accès à un emplacement non autorisé");
            }
        } catch (\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(EnterpriseType::class, $enterprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $roles = [];

            $projectRoot = $kernel->getProjectDir();
            $pdf = new Pdf($projectRoot.'/public/uploads/images/users/'.$enterprise->getBrochure());
            $pdf->saveImage($projectRoot.'/public/uploads/images/users/');

            $filesystem = new Filesystem();

            if ($enterprise->getBrochure() !== null) {
                $uniqueBrochure = explode('.', $enterprise->getBrochure());
                $filesystem->copy(
                    $projectRoot.'/public/uploads/images/users/1.jpg',
                    $projectRoot.'/public/uploads/images/users/'.$uniqueBrochure[0].'.jpg'
                );
            }

            if ($connectedUser) {
                $roles = $connectedUser->getRoles();
            }
            if (in_array("ROLE_ADMIN", $roles, false)) {
                return $this->redirectToRoute('enterprise_index');
            }
            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('enterprise/edit.html.twig', [
            'enterprise' => $enterprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<[0-9]{1,}>}/payment/{status<[0-1]>}", name="enterprise_payment_status")
     * @IsGranted("ROLE_ADMIN")
     */
    public function changePaymentStatus(
        Enterprise $enterprise,
        EntityManagerInterface $entityManager,
        int $status = 0
    ) :Response {

        $enterprise->setPaymentStatus($status);
        $entityManager->persist($enterprise);
        $entityManager->flush();

        return new Response("Ok");
    }
}
