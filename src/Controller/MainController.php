<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Gallery;
use App\Entity\Pronostic;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\GalleryType;
use App\Repository\UserRepository;
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
    public function home(EntityManagerInterface $em, UserRepository $userRepo): Response
    {
        /// >>> Récuperer le nombre de paris gagnés / perdu
        $number_win = count($em->getRepository(Pronostic::class)->findBy([
            'result' => 'G'
        ]));
        $number_loose = count($em->getRepository(Pronostic::class)->findBy([
            'result' => 'P'
        ]));

        /// <<< Récuperer le nombre de paris gagnés / perdu

        /// >>> Récuperer nombre de paris gagné sur les 5 derniers
        // $fifthPronostics = $em->getRepository(Pronostic::class)->findBy([
        //     'category' => 'F'
        // ],['createDate' => 'DESC'], 7);
        // $result_fifth = 0;

        // for ($i=0; $i < count($fifthPronostics); $i++) { 
        //     if ($fifthPronostics[$i]->getResult() == 'ND') {
        //         unset($fifthPronostics[$i]);
        //     }
        // }

        // while(count($fifthPronostics) > 5) { 
        //     array_pop($fifthPronostics);
        // }

        // foreach ($fifthPronostics as $key => $value) {
        //     if ($value->getResult() === 'G') {
        //         $result_fifth++;
        //     }
        // }
        /// <<< Récuperer nombre de paris gagné sur les 5 derniers

        if ($this->getUser()) {
            $isVIP = $userRepo->verifyVIP($this->getUser());
        } else {
            $isVIP = false;
        }

        return $this->render('base.html.twig', [
            // 'result_fifth' => $result_fifth,
            'number_win' => $number_win,
            'number_loose' => $number_loose,
            'isVIP' => $isVIP
        ]);
    }

    /**
     * @Route("/joinUs", name="join_us")
     */
    public function joinUs(UserRepository $userRepo): Response
    {
        if ($this->getUser()) {
            $isVIP = $userRepo->verifyVIP($this->getUser());
        } else {
            $isVIP = false;
        }

        return $this->render('join-us.html.twig', [
            'isVIP' => $isVIP
        ]);
    }

    /**
     * @Route("/mentions-legals", name="mentions_legals")
     */
    public function mentionsLegals(): Response
    {
        return $this->render('mentions-legals.html.twig', [
            'isVIP' => true
        ]);
    }

    /**
     * @Route("/community", name="community", methods={"GET", "POST"})
     */
    public function community(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, UserRepository $userRepo): Response
    {
        $gallery = $em->getRepository(Gallery::class)->findBy([
            'status' => 'P'
        ], [ 'createAt' => 'DESC' ]);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comments = $em->getRepository(Comment::class)->findBy([
            'status' => 'V'
        ],['createAt' => 'DESC']);
        
        $comments = $paginator->paginate(
            $comments, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );
            
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setStatus('W');
            $comment->setUser($this->getUser());
            $comment->setCreateAt(new DateTime('now'));

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($comment);
            $doctrine->flush();

            $this->addFlash('success', 'Commentaire posté avec succès. Cependant le commentaire doit être approuvé par l’administrateur pour être visible');
            return $this->redirectToRoute('community');
        }
        
        if ($this->getUser()) {
            $isVIP = $userRepo->verifyVIP($this->getUser());
        } else {
            $isVIP = false;
        }

        $gallery = $paginator->paginate(
            $gallery, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('community.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments,
            'gallery' => $gallery,
            'isVIP' => $isVIP
        ]);
    }

    /**
     * @Route("/history", name="history")
     */
    public function history(Request $request, PaginatorInterface $paginator, UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        $currentDate = new DateTime();

        $dql = "SELECT p FROM App:Pronostic p WHERE p.result = :statut1 OR p.result = :statut2 ORDER BY p.createDate DESC";
        $pronostics = $em->createQuery($dql);
        $pronostics->setParameter('statut1', 'G');
        $pronostics->setParameter('statut2', 'P');

        $pronostics = $paginator->paginate(
            $pronostics, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        if ($this->getUser()) {
            $isVIP = $userRepo->verifyVIP($this->getUser());
        } else {
            $isVIP = false;
        }

        return $this->render('history.html.twig', [
            'pronostics' => $pronostics,
            'isVIP' => $isVIP
        ]);
    }
}
