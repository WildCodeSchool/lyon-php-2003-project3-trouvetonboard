<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Skill;
use App\Repository\ProfileRepository;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchsController extends AbstractController
{
    /**
     * @Route("/matchs", name="matchs_index")
     */
    public function index(SkillRepository $skillRepository) :Response
    {
        $matchs = $skillRepository->findAll();
        return $this->render('matchs/index.php', ['matchs' => $matchs]);
    }
}
