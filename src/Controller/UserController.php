<?php

namespace App\Controller;

use App\Entity\Pronostic;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/VIP")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/currentPronostic", name="currentPronostic")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $currentDate = new DateTime();

        ///
        $infoVIP = $this->getUser();
        if ($infoVIP) {
            $isVIP = $infoVIP->getEndVip() > $currentDate ? true : false;
            $dateVIP = $infoVIP->getEndVip();
            if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
                $isVIP = true;
            }
        } else {
            $isVIP = false;
        }
        ///

        if (!$isVIP) {
            $this->addFlash('err', "Vous devez êtres VIP pour accéder à cette page !");
            return $this->redirectToRoute('home');
        }

        $currentProno = $em->getRepository(Pronostic::class)->findBy([
            'createAt' => $currentDate->format('d-m-Y')
        ],['createAt' => 'DESC'],2);

        return $this->render('user/current-prono.html.twig', [
            'currentProno' => $currentProno,
            'isVIP' => $isVIP,
            'dateVIP' => $dateVIP
        ]);
    }
}
