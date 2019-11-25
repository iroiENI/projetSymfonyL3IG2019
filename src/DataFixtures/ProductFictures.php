<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Category;

class ProductFictures extends Fixture
{/*
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        //4 categories fakes
        for($i=1; $i<=4; $i++){
            $categorie = new Category();
            $categorie->setLibelle($faker->sentence());
                
            $manager->persist($categorie);

            //pour produit 4 ou 6
            for($j=1; $j<=mt_rand(4,6); $j++){
                $produit = new Product();
                $produit->setDesignation($faker->sentence())
                        ->setPu(mt_rand(300,4000))
                        ->setFournisseur($faker->sentence())
                        ->setImage($faker->ImageUrl())
                        ->setCategory($categorie);

                $manager->persist($produit);
            }

        }

        $manager->flush();
    }*/
}
