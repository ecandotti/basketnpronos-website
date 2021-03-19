<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Gallery;
use App\Entity\Pronostic;
use App\Form\GalleryType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
    public function adminManagePronostic(Request $request, PaginatorInterface $paginator): Response
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

    /**
     * @Route("/manage-gallery", name="admin_manage_gallery")
     */
    public function adminManageGallery(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $gallery = $em->getRepository(Gallery::class)->findBy([],[
            'createAt' => 'DESC'
        ]);

        $gallery = $paginator->paginate(
            $gallery, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('admin/manage-gallery.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/manage-comment", name="admin_manage_comment")
     */
    public function adminManageComment(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $comments = $em->getRepository(Comment::class)->findBy([],[
            'createAt' => 'DESC'
        ]);

        $comments = $paginator->paginate(
            $comments, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('admin/manage-comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/manage?status={status}&commentId={commentId}", name="comment_action")
     */
    public function comment(Request $request, $status, $commentId)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->find($commentId);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No comment found for id '.$commentId
            );
        }
        if ($status == 'V') {
            $comment->setStatus('V');
            $em->flush();
            $this->addFlash('success', 'Message validé avec succès !');
        } elseif ($status == 'R') {
            $comment->setStatus('R');
            $em->flush();
            $this->addFlash('success', 'Message refusé avec succès !');
        } else {
            $this->addFlash('error', 'Erreur de l\'action du commentaire !');
            return $this->redirectToRoute('admin_manage_comment');
        }
        return $this->redirectToRoute('admin_manage_comment');
    }

    /**
     * @Route("/modify-home", name="admin_modify_home")
     */
    public function adminModifyHome(Request $request, PaginatorInterface $paginator): Response
    {
        $pronostiques = $this->getDoctrine()->getRepository(Pronostic::class)->findBy([],[
            'createDate' => 'DESC'
        ]);

        $pronostiques = $paginator->paginate(
            $pronostiques, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('admin/modify-home.html.twig', [
            'pronostiques' => $pronostiques
        ]);
    }

    /**
     * @Route("/add-gallery", name="admin_add_gallery")
     */
    public function adminAddGallery(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gallery->setCreateAt(new DateTime('now'));

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($gallery);
            $doctrine->flush();

            $this->addFlash('success', 'Contenue ajouté dans la galerie !');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/add-gallery.html.twig', [
            'form' => $form->createView(),
            'gallery' => $gallery
        ]);
    }
}
