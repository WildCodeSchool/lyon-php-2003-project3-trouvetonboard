<?php

namespace App\Controller;

use App\Entity\Advisor;
use App\Entity\Enterprise;
use App\Entity\User;
use App\Entity\Profile;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use \DateTime;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginAuthenticator $authenticator
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginAuthenticator $authenticator
    ): Response {

        if ($this->getUser()) {
            $this->addFlash("warning", "Vous n'avez pas accès a cette page");
            return $this->redirectToRoute("home");
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            if ($form->get('type')->getData() == "enterprise") {
                $enterprise = new Enterprise();
                $enterprise->setName("");
                $entityManager->persist($enterprise);
                $user->setEnterprise($enterprise);
                $user->setRoles(['ROLE_ENTERPRISE']);
                $user->setType('Entreprise');
            } else {
                $advisor = new Advisor();
                $entityManager->persist($advisor);
                $user->setAdvisor($advisor);
                $user->setRoles(['ROLE_ADVISOR']);
                $user->setType('Advisor');

                $profile = new Profile();
                $profile->setPaymentType("All");
                $profile->setTitle('');
                $profile->setIsPropose(true);
                $profile->setIsRequest(false);
                $profile->setDateCreation(new DateTime("now"));
                $profile->setAdvisor($advisor);
                $entityManager->persist($profile);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            // if added for travis check problem
            $userEmail = "";
            $emailAddress = "";
            if ($user->getEmail()) {
                $userEmail = $user->getEmail();
                $emailAddress = new Address($userEmail);
            }
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address($this->getParameter('mailer_from'), 'TTB Mail Confirmation Bot'))
                    ->to($emailAddress)
                    ->subject('Bienvenue sur TTB')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $this->addFlash("success", "Veuillez valider votre compte en cliquant sur le lien envoyé.");
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            ) ?: $this->redirectToRoute('user_profile_show');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(), 'user' => $user
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse mail a bien été vérifiée');

        return $this->redirectToRoute('home');
    }
}
