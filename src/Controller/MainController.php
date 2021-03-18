<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Pronostic;
use App\Form\CommentType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
    public function notice(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comments = $em->getRepository(Comment::class)->findBy([],[
            'createAt' => 'DESC'
        ]);
        
        $comments = $paginator->paginate(
            $comments, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
            
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setStatus('W');
            $comment->setUser($this->getUser());
            $comment->setCreateAt(new DateTime('now'));

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($comment);
            $doctrine->flush();

            $this->addFlash('success', 'Commentaire posté avec succès. Cependant le commentaire doit être approuvé par l’administrateur pour être visible');
            return $this->render('notice.html.twig', [
                'form' => $form->createView(),
                'comments' => $comments
            ]);
        }

        return $this->render('notice.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments
        ]);
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
