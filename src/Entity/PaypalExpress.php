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
    public $paypalClientID = "AWP4Q5CuQ6R5kv7Q04gB0h7EMXzrLgPklEnqYN6OqoddbyclEQQmjC6jZfTLDMBFFwEaN11BJ7mS5xxJ";
    private $paypalSecret = "EF1eV2tvzcXp0FOAq77jXUQWIUUV0sX4zTmLAJc3PMqFLQnO62LfgsEVwS9m31HK8IHakgaF5oAc2uwu";

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

