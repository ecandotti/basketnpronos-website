<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category2 = new Category();

        $category->setName('Fiable');
        $category2->setName('Fun');

        $manager->persist($category);
        $manager->persist($category2);
        $manager->flush();
    }
}
