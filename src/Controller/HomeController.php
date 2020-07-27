<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/infoEnterprise", name="info_enterprise")
     */
    public function infoEnterprise() :Response
    {
        return $this->render('home/infoEnterprise.html.twig');
    }

    /**
     * @Route("/infoAdvisor", name="info_advisor")
     */
    public function infoAdvisor() :Response
    {
        return $this->render('home/infoAdvisor.html.twig');
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu() :Response
    {
        return $this->render('home/cgu.html.twig');
    }

    /**
     * @Route("/mentionslegales", name="legalmentions")
     */
    public function legalMentions() :Response
    {
        return $this->render('home/legalmentions.html.twig');
    }
}
