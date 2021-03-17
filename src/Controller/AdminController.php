<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'latest_pronostic' => null,
        ]);
    }

    /**
     * @Route("/dashboard", name="admin_manage_pronostic")
     */
    public function adminManage(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'latest_pronostic' => null,
        ]);
    }
}
