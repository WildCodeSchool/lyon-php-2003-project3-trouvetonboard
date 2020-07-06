<?php


namespace App\Controller;

use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render("admin/index.html.twig");
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function stats(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $enterprises = $userRepository->countByRole("ROLE_ENTERPRISE");
        $advisors = $userRepository->countByRole("ROLE_ADVISOR");
        $users = $userRepository->countAllUsers();
        $boardRequest = $profileRepository->countBoardRequest();


        return $this->render("admin/stats.html.twig", [
            'enterprises' => end($enterprises),
            'advisors' => end($advisors),
            'users' => end($users),
            'boardRequest' => end($boardRequest)
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function allUsers(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        dump($users);
        return $this->render("admin/stats.html.twig", [
            'users' => $users,
        ]);
    }
}
