<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $article = new Product();
            $article->setName($faker->word);
            $article->setResume($faker->realText($maxNbChars = 1500, $indexSize = 2));
            $article->setPrice($faker->randomFloat($nbMaxDecimals = null, $min = 50, $max = 999));
            $manager->persist($article);
            $manager->flush();

        }
    }


}
