<?php

namespace App\Controller;

use App\Entity\Pronostic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function adminDashboard(): Response
    {
        $latest_prono = $this->getDoctrine()->getRepository(Pronostic::class)->findBy([
            'user' => $this->getUser()
        ], [ 'createDate' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'latest_pronostic' => $latest_prono,
        ]);
    }

    /**
     * @Route("/manage-pronostic", name="admin_manage_pronostic")
     */
    public function adminManage(Request $request, PaginatorInterface $paginator): Response
    {
        $pronostiques = $this->getDoctrine()->getRepository(Pronostic::class)->findBy([],[
            'createDate' => 'DESC'
        ]);

        $pronostiques = $paginator->paginate(
            $pronostiques, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('admin/manage-prono.html.twig', [
            'pronostiques' => $pronostiques
        ]);
    }
}
