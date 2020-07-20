<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        if ($user) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('home');
            } else {
                return $this->redirectToRoute('user_profile_show');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $errors = null;
        if ($error) {
            if ($error->getMessageKey() == "Invalid credentials.") {
                $errors = "Mot de passe invalide.";
            } else {
                $errors = "Email inconnu.";
            }
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $errors
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        /*throw new \LogicException('This method can be blank
        - it will be intercepted by the logout key on your firewall.');*/
    }

    /**
     * @Route("/resetpass", name="app_forgotten_password")
     */
    public function resetPass(
        Request $request,
        UserRepository $users,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer
    ) :Response {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $datas = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
            $user = $users->findOneBy(['email' => $datas['email']]);

            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('app_forgotten_password');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl(
                'app_reset_password',
                array('token' => $token),
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            // On génère l'e-mail
            $emailAddress = $user->getEmail();

            $message = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($emailAddress)
                ->subject('\'Mot de passe oublié\'')
                ->html(
                    $this->renderView('security/reset_password_email.html.twig', [
                    'url' => $url,
                    ])
                );

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash de confirmation
            $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé');

            // On redirige vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // On envoie le formulaire à la vue
        return $this->render('security/forgotten_password.html.twig', [
            'emailForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(
        Request $request,
        string $token,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) :Response {
        // On cherche un utilisateur avec le token donné
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            if (strlen($request->request->get('password')) >= 8) {
                if ($request->request->get('password') == $request->request->get('passwordRepeat')) {
                    // On supprime le token

                    $user->setResetToken(null);

                    // On chiffre le mot de passe
                    $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

                    // On stocke
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // On crée le message flash
                    $this->addFlash('success', 'Mot de passe mis à jour');

                    // On redirige vers la page de connexion
                    return $this->redirectToRoute('app_login');
                }
                $this->addFlash('danger', 'Les mots de passe doivent être identiques');
                return $this->render('security/reset_password.html.twig', ['token' => $token]);
            } else {
                $this->addFlash('danger', 'Votre mot de passe doit comporter au minimum 8 caractères');
                return $this->render('security/reset_password.html.twig', ['token' => $token]);
            }
        } else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
    }
}
