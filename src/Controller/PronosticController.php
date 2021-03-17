<?php

namespace App\Controller;

use App\Entity\Pronostic;
use App\Form\PronosticType;
use App\Repository\PronosticRepository;
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
     * @Route("/", name="pronostic_index", methods={"GET"})
     */
    public function index(PronosticRepository $pronosticRepository): Response
    {
        return $this->render('pronostic/index.html.twig', [
            'pronostics' => $pronosticRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pronostic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pronostic = new Pronostic();
        $form = $this->createForm(PronosticType::class, $pronostic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pronostic);
            $entityManager->flush();

            return $this->redirectToRoute('pronostic_index');
        }

        return $this->render('pronostic/new.html.twig', [
            'pronostic' => $pronostic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pronostic_show", methods={"GET"})
     */
    public function show(Pronostic $pronostic): Response
    {
        return $this->render('pronostic/show.html.twig', [
            'pronostic' => $pronostic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pronostic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pronostic $pronostic): Response
    {
        $form = $this->createForm(PronosticType::class, $pronostic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pronostic_index');
        }

        return $this->render('pronostic/edit.html.twig', [
            'pronostic' => $pronostic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pronostic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pronostic $pronostic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pronostic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pronostic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pronostic_index');
    }
}
