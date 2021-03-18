<?php

namespace App\Controller;

use App\Entity\Pronostic;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/notice", name="notice")
     */
    public function notice(): Response
    {
        return $this->render('notice.html.twig');
    }

    /**
     * @Route("/joinUs", name="join_us")
     */
    public function joinUs(): Response
    {
        return $this->render('join-us.html.twig');
    }

    /**
     * @Route("/gallery", name="gallery")
     */
    public function gallery(): Response
    {
        return $this->render('gallery.html.twig');
    }

    /**
     * @Route("/history", name="history")
     */
    public function history(Request $request, PaginatorInterface $paginator): Response
    {
        $pronostiques = $this->getDoctrine()->getRepository(Pronostic::class)->findBy([],[
            'createDate' => 'DESC'
        ]);

        $pronostiques = $paginator->paginate(
            $pronostiques, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('history.html.twig', [
            'pronostiques' => $pronostiques
        ]);
    }
}
