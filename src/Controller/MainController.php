<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/", name="notice")
     */
    public function notice(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/", name="join_us")
     */
    public function joinUs(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/", name="gallery")
     */
    public function gallery(): Response
    {
        return $this->render('base.html.twig');
    }
}
