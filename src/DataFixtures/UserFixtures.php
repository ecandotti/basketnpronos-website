<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setEmail('admin@basketnprono.fr')
            ->setPseudo('ADMIN')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setIsVerified(true)
            ->setIsVIP(true)
            ->setRoles(['ROLE_ADMIN'])
        ;
        
        $manager->persist($user);
        $manager->flush();
    }
}
