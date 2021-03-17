<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/dashboard", name="user_dashboard")
     */
    public function index(): Response
    {
        return $this->render('user/current-prono.html.twig', [
            'currentProno' => null,
        ]);
    }
}
