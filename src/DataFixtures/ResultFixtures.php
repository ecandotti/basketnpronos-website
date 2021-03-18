<?php

namespace App\DataFixtures;

use App\Entity\Result;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ResultFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $result = new Result();
        $result2 = new Result();
        $result3 = new Result();

        $result->setName('ND');
        $result2->setName('G');
        $result3->setName('P');

        $manager->persist($result);
        $manager->persist($result2);
        $manager->persist($result3);
        $manager->flush();
    }
}
