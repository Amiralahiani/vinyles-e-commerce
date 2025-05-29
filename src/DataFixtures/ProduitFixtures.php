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

                // Artiste 4
        $artist4 = new Artist();
        $artist4->setName('The Beatles');
        $manager->persist($artist4);

        $product4 = new Produit();
        $product4->setTitle('Abbey Road');
        $product4->setArtist($artist4);
        $product4->setDescription('Vinyle classique du légendaire album "Abbey Road" des Beatles.');
        $product4->setPrice(159.99);
        $product4->setImage('uploads/produits/beatles.png');
        $product4->setStock(4);
        $product4->setCategory($rock);
        $manager->persist($product4);

        // Artiste 5
        $artist5 = new Artist();
        $artist5->setName('Adele');
        $manager->persist($artist5);

        $product5 = new Produit();
        $product5->setTitle('21');
        $product5->setArtist($artist5);
        $product5->setDescription('Vinyle de l\'album "21" d\'Adele, incluant les tubes "Someone Like You" et "Rolling in the Deep".');
        $product5->setPrice(139.99);
        $product5->setImage('uploads/produits/adele.webp');
        $product5->setStock(6);
        $product5->setCategory($pop);
        $manager->persist($product5);

        // Artiste 6
        $artist6 = new Artist();
        $artist6->setName('Nirvana');
        $manager->persist($artist6);

        $product6 = new Produit();
        $product6->setTitle('Nevermind');
        $product6->setArtist($artist6);
        $product6->setDescription('Vinyle de l\'album culte "Nevermind" de Nirvana.');
        $product6->setPrice(179.99);
        $product6->setImage('uploads/produits/nirvana.jpg');
        $product6->setStock(3);
        $product6->setCategory($rock);
        $manager->persist($product6);

        $lana = new Artist();
        $lana->setName('Lana Del Rey');
        $manager->persist($lana);

        $productLana = new Produit();
        $productLana->setTitle('Born to Die');
        $productLana->setArtist($lana);
        $productLana->setDescription('L’album emblématique "Born to Die" de Lana Del Rey en édition vinyle deluxe.');
        $productLana->setPrice(189.99);
        $productLana->setImage('uploads/produits/lana.webp'); // Assure-toi que cette image existe dans ton projet
        $productLana->setStock(4);
        $productLana->setCategory($pop);
        $manager->persist($productLana);

        $sabrina = new Artist();
        $sabrina->setName('Sabrina Carpenter');
        $manager->persist($sabrina);

        $productSabrina = new Produit();
        $productSabrina->setTitle('Emails I Can’t Send');
        $productSabrina->setArtist($sabrina);
        $productSabrina->setDescription('Vinyle de l’album "Emails I Can’t Send" de Sabrina Carpenter, édition spéciale.');
        $productSabrina->setPrice(169.99);
        $productSabrina->setImage('uploads/produits/sabrina.webp'); // Assure-toi que cette image est présente
        $productSabrina->setStock(6);
        $productSabrina->setCategory($pop);
        $manager->persist($productSabrina);

        $shawn = new Artist();
        $shawn->setName('Shawn Mendes');
        $manager->persist($shawn);

        $productShawn = new Produit();
        $productShawn->setTitle('HandWritten');
        $productShawn->setArtist($shawn);
        $productShawn->setDescription('Le vinyle de l’album "HandWritten" de Shawn Mendes, avec les hits "Mercy" et "Treat You Better".');
        $productShawn->setPrice(159.99);
        $productShawn->setImage('uploads/produits/shawn.webp');
        $productShawn->setStock(6);
        $productShawn->setCategory($pop);
        $manager->persist($productShawn);
        
        $billie = new Artist();
        $billie->setName('Billie Eilish');
        $manager->persist($billie);

        $productBillie = new Produit();
        $productBillie->setTitle('When We All Fall Asleep, Where Do We Go?');
        $productBillie->setArtist($billie);
        $productBillie->setDescription('Vinyle de l’album révolutionnaire de Billie Eilish avec les morceaux "Bad Guy", "bury a friend" et "You Should See Me in a Crown".');
        $productBillie->setPrice(179.99);
        $productBillie->setImage('uploads/produits/billie.jpg');
        $productBillie->setStock(4);
        $productBillie->setCategory($pop);
        $manager->persist($productBillie);

        $lauryn = new Artist();
        $lauryn->setName('Lauryn Hill');
        $manager->persist($lauryn);

        $productLauryn = new Produit();
        $productLauryn->setTitle('The Miseducation of Lauryn Hill');
        $productLauryn->setArtist($lauryn);
        $productLauryn->setDescription('Vinyle de l’album mythique "The Miseducation of Lauryn Hill", fusionnant R&B, soul et hip-hop.');
        $productLauryn->setPrice(149.99);
        $productLauryn->setImage('uploads/produits/lauryn.webp');
        $productLauryn->setStock(5);
        $productLauryn->setCategory($pop);
        $manager->persist($productLauryn);

        $whitney = new Artist();
        $whitney->setName('Whitney Houston');
        $manager->persist($whitney);

        $productWhitney = new Produit();
        $productWhitney->setTitle('The Bodyguard: Original Soundtrack Album');
        $productWhitney->setArtist($whitney);
        $productWhitney->setDescription('La bande originale du film "The Bodyguard", avec le légendaire "I Will Always Love You". Un classique absolu en vinyle.');
        $productWhitney->setPrice(139.99);
        $productWhitney->setImage('uploads/produits/whitney.webp');
        $productWhitney->setStock(4);
        $productWhitney->setCategory($pop);
        $manager->persist($productWhitney);

        $mariah = new Artist();
        $mariah->setName('Mariah Carey');
        $manager->persist($mariah);

        $productMariah = new Produit();
        $productMariah->setTitle('DreamLover');
        $productMariah->setArtist($mariah);
        $productMariah->setDescription('Vinyle de l’album "DreamLover" de Mariah Carey, avec les incontournables "Hero" et "Without You".');
        $productMariah->setPrice(149.99);
        $productMariah->setImage('uploads/produits/mariah.webp');
        $productMariah->setStock(5);
        $productMariah->setCategory($pop);
        $manager->persist($productMariah);

        $weeknd = new Artist();
        $weeknd->setName('The Weeknd');
        $manager->persist($weeknd);

        $productWeeknd = new Produit();
        $productWeeknd->setTitle('StarBoy');
        $productWeeknd->setArtist($mariah);
        $productWeeknd->setDescription('Vinyle de l’album "StarBoy" de Mariah Carey, avec les incontournables "Hero" et "Without You".');
        $productWeeknd->setPrice(149.99);
        $productWeeknd->setImage('uploads/produits/weeknd.webp');
        $productWeeknd->setStock(5);
        $productWeeknd->setCategory($pop);
        $manager->persist($productWeeknd);

        






        $manager->flush();
    }
}