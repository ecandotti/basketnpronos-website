<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Gallery;
use App\Entity\Pronostic;
use App\Form\CommentType;
use App\Form\GalleryType;
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
    public function home(EntityManagerInterface $em): Response
    {
        /// >>> Récuperer le mois actuelle
        $currMonth = new DateTime('now');
        $months = [ '03' => 'Janvier',
                    '02' => 'Fevrier',
                    '03' => 'Mars',
                    '04' => 'Avril',
                    '05' => 'Mai',
                    '06' => 'Juin',
                    '07' => 'Juillet',
                    '08' => 'Août',
                    '09' => 'Septembre',
                    '10' => 'Octobre',
                    '11' => 'Novembre',
                    '12' => 'Décembre'
        ];
        /// <<< Récuperer le mois actuelle

        /// >>> Récuperer le nombre de paris gagnés / perdu
        $number_win = count($em->getRepository(Pronostic::class)->findBy([
            'result' => 'G'
        ]));
        $number_loose = count($em->getRepository(Pronostic::class)->findBy([
            'result' => 'P'
        ]));

        /// <<< Récuperer le nombre de paris gagnés / perdu

        /// >>> Récuperer nombre de paris gagné sur les 5 derniers
        $fifthPronostiques = $em->getRepository(Pronostic::class)->findBy([
            'category' => 'F'
        ],['createDate' => 'DESC'], 7);
        $result_fifth = 0;

        for ($i=0; $i < count($fifthPronostiques); $i++) { 
            if ($fifthPronostiques[$i]->getResult() === 'ND') {
                unset($fifthPronostiques[$i]);
            }
        }

        while(count($fifthPronostiques) > 5) { 
            array_pop($fifthPronostiques);
        }

        foreach ($fifthPronostiques as $key => $value) {
            if ($value->getResult() === 'G') {
                $result_fifth++;
            }
        }
        /// <<< Récuperer nombre de paris gagné sur les 5 derniers

        return $this->render('base.html.twig', [
            'month' => $months[$currMonth->format('m')],
            'result_fifth' => $result_fifth,
            'number_win' => $number_win,
            'number_loose' => $number_loose
        ]);
    }

    /**
     * @Route("/notice", name="notice", methods={"GET", "POST"})
     */
    public function notice(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comments = $em->getRepository(Comment::class)->findBy([
            'status' => 'V'
        ],['createAt' => 'DESC']);
        
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
            return $this->redirectToRoute('notice');
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
     * @Route("/community", name="community")
     */
    public function community(EntityManagerInterface $em): Response
    {
        return $this->render('community.html.twig');
    }

    /**
     * @Route("/history", name="history")
     */
    public function history(Request $request, PaginatorInterface $paginator): Response
    {
        $currentDate = new DateTime();

        $pronostiques = $this->getDoctrine()->getRepository(Pronostic::class)->findBy([]);

        foreach ($pronostiques as $key => $value){
            if ($value->getCreateAt() == $currentDate->format('d-m-Y')) {
                unset($pronostiques[$key]);
            }
        }

        $pronostiques = $paginator->paginate(
            $pronostiques, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('history.html.twig', [
            'pronostiques' => $pronostiques
        ]);
    }
}
