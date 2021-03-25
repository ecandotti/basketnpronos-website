<?php

namespace App\Controller;

use App\Entity\PaypalExpress;
use App\Repository\PaypalExpressRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paypal")
 */
class PaypalController extends AbstractController
{
    /**
     * @Route("/checkout/{priceId}", name="checkout_paypal")
     */
    public function checkoutPaypal($priceId, PaypalExpressRepository $paypalRepo, UserRepository $userRepo): Response
    {
        if ($this->getUser() and !$userRepo->verifyVIP($this->getUser())) {
            $token = $paypalRepo->getToken();
            $info = $paypalRepo->whichFormule($priceId);
            if (!$info) {
                $this->addFlash('err', 'Error d\'id !');
                return $this->redirectToRoute('home');
            }
            $url = $paypalRepo->setupPayment($token, $priceId);
    
            header("Location: $url");
            exit;
        } else {
            $this->addFlash('err', 'Vous n\'êtes pas identifié');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/capture", name="paypal_capture")
     */
    public function capturePaypal(Request $request, PaypalExpressRepository $paypalRepo, EntityManagerInterface $em): Response
    {
        $token = $paypalRepo->getToken();
        $tokenReq = $request->get('token');
        $result = $paypalRepo->capturePayment($tokenReq, $token);
        if ($result and $result->status == "COMPLETED") {
            $datetime = new DateTime('now');
            $user = $this->getUser();
            $info = $paypalRepo->whichFormule($result->purchase_units[0]->payments->captures[0]->custom_id);
            $user->setStartVip(new DateTime('now'));
            $user->setEndVip(date_add($datetime, date_interval_create_from_date_string($info['month'] . ' months')));
            $em->flush();
            $em->persist($user);
            $this->addFlash('VIP', "Félicitation ! Vous avez rejoind le V.I.P");
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('errVIP', "Une erreur est survenu, contacter le responsable du site : contact@basketnpronos.fr");
            return $this->redirectToRoute('home');
        }
    }
}
