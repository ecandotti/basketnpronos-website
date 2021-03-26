<?php

namespace App\Entity;

use App\Repository\PaypalExpressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaypalExpressRepository::class)
 */
class PaypalExpress
{
    public $paypalEnv = "live";
    public $paypalURLToken = "https://api-m.paypal.com/v1/";
    public $paypalURL = "https://api-m.paypal.com/v2/";
    public $paypalClientID = "AYaBzHavlRGQ54FCfuRvgpgcFI43V8oBsMQNi9W8WK9YD_2F87QdJofs-tt3zvMg6hF53POUk-oWlA1u";
    private $paypalSecret = "EHmRvz4gwI7O51Y_XHkZa4yfKiphEtYPi3NdKP5wthp0P9bAEX0x8yQ7b8JUCaFp4qq-CG8Mi_vyp962";

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

