<?php

namespace App\Repository;

use App\Entity\PaypalExpress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function setupPayment($token) {
        $paypalExpress = new PaypalExpress();
        $url = $paypalExpress->getPaypalURL();

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
        curl_setopt($ch, CURLOPT_POSTFIELDS, '
        {
            "intent": "CAPTURE",
            "purchase_units": [{
                "custom_id": "2",
                "description": "Souscription à labonnement",
                "amount": {
                    "currency_code": "USD",
                    "value": "10.00"
                }
            }],
            "application_context": {
                "brand_name": "BasketNPronos",
                "return_url": "https://localhost:8000/paypal/capture",
                "cancel_url": "https://localhost:8000/"
            }
        }');
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
