<?php

namespace App\Repository;

use App\Entity\PaypalExpress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method PaypalExpress|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaypalExpress|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaypalExpress[]    findAll()
 * @method PaypalExpress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaypalExpressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaypalExpress::class);
    }

    public function whichFormule(string $id)
    {
        switch ($id) {
            case '1':
                $month = '3';
                $price = '21.99';
                break;
            case '2':
                $month = '1';
                $price = '8.99';
                break;
            case '3':
                $month = '12';
                $price = '69.99';
                break;
            default:
                return false;
        }

        return ['month' => $month, 'price' => $price];
    }

    public function getToken()
    {
        $paypalExpress = new PaypalExpress();
        $url = $paypalExpress->getPaypalURLToken();
        $clientID = $paypalExpress->getPaypalClientID();
        $secret = $paypalExpress->getPaypalSecret();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'oauth2/token');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientID.":".$secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            $jsonData = json_decode($response);
            return $jsonData->access_token;
        } else {
            $response;
        }
    }

    public function setupPayment($token, $priceId) {
        $paypalExpress = new PaypalExpress();
        $url = $paypalExpress->getPaypalURL();

        $formule = $this->whichFormule($priceId);
        $data = new stdClass();

        $purshase_units = new stdClass();
        $purshase_units->custom_id = $priceId;
        $purshase_units->description = "Abonnement BasketNPronos";

        $amount = new stdClass();
        $amount->currency_code = "EUR";
        $amount->value = $formule['price'];
        $purshase_units->amount = $amount;

        $application_context = new stdClass();
        $application_context->brand_name = "BasketNPronos";
        $application_context->return_url = "https://www.basketnpronos.fr/paypal/capture";
        $application_context->cancel_url = "https://www.basketnpronos.fr/";

        $data->intent = "CAPTURE";
        $data->purchase_units = array($purshase_units);
        $data->application_context = $application_context;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'checkout/orders');
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json' 
        )); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);
        return $result->links[1]->href;
    }

    public function capturePayment($payerID, $token)
    {
        $paypalExpress = new PaypalExpress();
        $url = $paypalExpress->getPaypalURL();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'checkout/orders/'. $payerID .'/capture');
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json' 
        )); 
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);
        return $result;
    }
}
