<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchsController extends AbstractController
{

    /**
     * @Route("/matchs", name="matchs_index")
     */
    public function index() :Response
    {
        return $this->render('matchs/index.html.twig');
    }
}