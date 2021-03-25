<?php

namespace App\Entity;

use App\Repository\PaypalExpressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaypalExpressRepository::class)
 */
class PaypalExpress
{
    public $paypalEnv = "sandbox";
    public $paypalURLToken = "https://api-m.sandbox.paypal.com/v1/";
    public $paypalURL = "https://api-m.sandbox.paypal.com/v2/";
    public $paypalClientID = "Ac9RS3gBkSzsmcBEZkb6D3fH4lIakqjLMQzPpsiSBWVGmEbVKc-8QOnO2m8M4V-uuRlX_oP9fZowrYTe";
    private $paypalSecret = "ELcAWXCvbLSj8czUbDYGj7WKMDk2evIiHuVZCclRYPhrbYMh9noGjYsMzVXHSl3IkelAUn5Ztkwi4QUb";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of paypalenv
     */ 
    public function getPaypalEnv()
    {
        return $this->paypalEnv;
    }

    /**
     * Get the value of paypalURL
     */ 
    public function getPaypalURL()
    {
        return $this->paypalURL;
    }

    /**
     * Get the value of paypalClientID
     */ 
    public function getPaypalClientID()
    {
        return $this->paypalClientID;
    }

    /**
     * Get the value of paypalSecret
     */ 
    public function getPaypalSecret()
    {
        return $this->paypalSecret;
    }

    /**
     * Get the value of paypalURLToken
     */ 
    public function getPaypalURLToken()
    {
        return $this->paypalURLToken;
    }
}

