<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

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
            ->setCreateAt(new DateTime('now'))
            ->setRoles(['ROLE_ADMIN'])
        ;
        
        $manager->persist($user);
        $manager->flush();
    }
}
