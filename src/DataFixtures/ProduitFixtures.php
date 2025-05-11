<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Artist;
use App\Entity\Produit;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Category
        $pop = new Category();
        $pop->setName('Pop');
        $manager->persist($pop);

        $rock = new Category();
        $rock->setName('Rock');
        $manager->persist($rock);

        // Artists
        $duaLipa = new Artist();
        $duaLipa->setName('Dua Lipa');
        $manager->persist($duaLipa);

        $michaelJackson = new Artist();
        $michaelJackson->setName('Michael Jackson');
        $manager->persist($michaelJackson);

        $metallica = new Artist();
        $metallica->setName('Metallica');
        $manager->persist($metallica);

        // Products
        $product1 = new Produit();
        $product1->setTitle('Vinyle Zoetrope');
        $product1->setArtist($duaLipa);
        $product1->setDescription('Limited Blood Records edition of Dua Lipa\'s debut studio album pressed as zoetrope picture disc. Limited to 10,000 copies worldwide.');
        $product1->setPrice(289.99);
        $product1->setImage('uploads/produits/douaa.jpg');
        $product1->setStock(3);
        $product1->setCategory($pop);
        $manager->persist($product1);

        $product2 = new Produit();
        $product2->setTitle('Thriller');
        $product2->setArtist($michaelJackson);
        $product2->setDescription('Michael Jackson\'s iconic album "Thriller" featuring legendary tracks.');
        $product2->setPrice(199.99);
        $product2->setImage('uploads/produits/mj.jpg');
        $product2->setStock(5);
        $product2->setCategory($pop);
        $manager->persist($product2);

        $product3 = new Produit();
        $product3->setTitle('Nothing Else Matters');
        $product3->setArtist($metallica);
        $product3->setDescription('Metallica\'s classic track "Nothing Else Matters" on a limited edition vinyl.');
        $product3->setPrice(249.99);
        $product3->setImage('uploads/produits/met.jpg');
        $product3->setStock(7);
        $product3->setCategory($rock);
        $manager->persist($product3);

        $manager->flush();
    }
}