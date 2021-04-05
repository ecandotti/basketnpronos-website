<?php

namespace App\Controller;

use App\Entity\Pronostic;
use App\Form\PronosticType;
use App\Repository\PronosticRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pronostic")
 */
class PronosticController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="pronostic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pronostic = new Pronostic();
        $form = $this->createForm(PronosticType::class, $pronostic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('content')->getData() == null) {
                $this->addFlash('err', 'Le contenu est vide, le pronostique n\'a pas été créé');
                return $this->redirectToRoute('admin_dashboard');
            }
            
            $pronostic->setCreateAt(new DateTime('now'));
            $pronostic->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pronostic);
            $entityManager->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('pronostic/new.html.twig', [
            'pronostic' => $pronostic,
            'form' => $form->createView(),
            'isVIP' => true
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="pronostic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pronostic $pronostic): Response
    {
        $form = $this->createForm(PronosticType::class, $pronostic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Pronostique édité avec succès !');
            return $this->redirectToRoute('admin_manage_pronostic');
        }

        return $this->render('pronostic/edit.html.twig', [
            'pronostic' => $pronostic,
            'form' => $form->createView(),
            'isVIP' => true
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="pronostic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pronostic $pronostic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pronostic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pronostic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
