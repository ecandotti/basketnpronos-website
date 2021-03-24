<?php

namespace App\Controller;

use App\Entity\PaypalExpress;
use App\Repository\PaypalExpressRepository;
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
     * @Route("/index", name="paypal_test")
     */
    public function index(): Response
    {
        return $this->render('paypal/index.html.twig', [
            'controller_name' => 'PaypalController',
        ]);
    }

    /**
     * @Route("/checkout/{priceId}", name="checkout_paypal")
     */
    public function checkoutPaypal(Request $request, $priceId, PaypalExpressRepository $paypalRepo): Response
    {
        if($request){
            $token = $paypalRepo->getToken();
            if ($priceId == 1 || $priceId == 2 || $priceId == 3) {
                switch ($priceId) {
                    case 1:
                        $price = "21.99";
                        $during = "3 mois";
                        break;
                    case 2:
                        $price = "8.99";
                        $during = "1 mois";
                        break;
                    case 3:
                        $price = "69.99";
                        $during = "1 an";
                        break;
                    
                    default:
                        $this->addFlash('error', 'Error est survenue !');
                        return $this->redirectToRoute('home');
                        break;
                }
            } else {
                $this->addFlash('error', 'Error d\'id !');
                return $this->redirectToRoute('home');
            }
        }

        $url = $paypalRepo->setupPayment($token);

        header("Location: $url");
        exit;
    }

    /**
     * @Route("/capture", name="paypal_capture")
     */
    public function capturePaypal(Request $request, PaypalExpressRepository $paypalRepo): Response
    {
        $token = $paypalRepo->getToken();
        $tokenReq = $request->get('token');
        $result = $paypalRepo->capturePayment($tokenReq, $token);
        if ($result->status == "COMPLETED") {
            $this->addFlash('VIP', "Félicitation ! Vous avez rejoind le V.I.P");
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('errVIP', "Une erreur est survenu, contacter le responsable du site : help@basketnpronos.fr");
            return $this->redirectToRoute('home');
        }
    }
}
