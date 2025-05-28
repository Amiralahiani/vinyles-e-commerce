<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ArtistRepository;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use App\Entity\Category;
use App\Entity\Artist;
use App\Form\ProduitForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdminController extends AbstractController
{
#[Route('/admin', name: 'admin_dashboard')]
#[IsGranted('ROLE_ADMIN')]
public function index(
    ProduitRepository $produitRepository,
    CommandeRepository $commandeRepository,
    UserRepository $userRepository
): Response {
    $totalProduits = $produitRepository->createQueryBuilder('p')
        ->select('SUM(p.stock)')
        ->getQuery()
        ->getSingleScalarResult();

    $totalCommandes = $commandeRepository->count([]);
    $totalUtilisateurs = $userRepository->count([]);

    return $this->render('admin/index.html.twig', [
        'totalProduits' => $totalProduits,
        'totalCommandes' => $totalCommandes,
        'totalUtilisateurs' => $totalUtilisateurs,
    ]);
}   
#[Route('/admin/produits/{id}', name: 'admin_produit_delete', methods: ['POST'])]
public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
        $entityManager->remove($produit);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a été supprimé.');
    }

    return $this->redirectToRoute('admin_produit_index');
}


        
    #[Route('/admin/produits', name: 'admin_produit_index')]
    public function produits(ProduitRepository $produitRepository): Response
    {
        return $this->render('admin/produits/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    
    #[Route('/admin/commandes', name: 'admin_commande_index')]
    public function commandes(CommandeRepository $commandeRepository): Response
    {
        return $this->render('admin/commandes/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
    #[Route('/admin/new', name: 'admin_produit_new')]
public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, CategoryRepository $categoryRepository, ArtistRepository $artistRepository): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitForm::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de la catégorie
        $categoryName = $form->get('category_name')->getData();
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) {
            $category = new Category();
            $category->setName($categoryName);
            $em->persist($category);
        }

        // Gestion de l'artiste
        $artistName = $form->get('artist_name')->getData();
        $artist = $artistRepository->findOneBy(['name' => $artistName]);
        if (!$artist) {
            $artist = new Artist();
            $artist->setName($artistName);
            $em->persist($artist);
        }

        $produit->setCategory($category);
        $produit->setArtist($artist);

        // Gestion de l'image
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('uploads_directory'), 
                    $newFilename
                );
            } catch (FileException $e) {
                // gestion de l'erreur (log ou message utilisateur)
            }

            $produit->setImage('uploads/produits/'.$newFilename);
        }

        $em->persist($produit);
        $em->flush();

        return $this->redirectToRoute('admin_produit_index');
    }

    return $this->render('admin/produits/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/admin/edit/{id}', name: 'admin_produit_edit')]
public function edit(
    Produit $produit,
    Request $request,
    EntityManagerInterface $em,
    ArtistRepository $artistRepository,
    CategoryRepository $categoryRepository,
    SluggerInterface $slugger
): Response {
    if ($request->isMethod('POST')) {
        // Titre, description, prix, stock
        $produit->setTitle($request->request->get('nom'));
        $produit->setDescription($request->request->get('description'));
        $produit->setPrice((float) $request->request->get('prix'));
        $produit->setStock((int) $request->request->get('stock'));

        // Artiste
        $artistName = trim($request->request->get('artiste'));
        $artist = $artistRepository->findOneBy(['name' => $artistName]);
        if (!$artist && $artistName !== '') {
            $artist = new Artist();
            $artist->setName($artistName);
            $em->persist($artist);
            $em->flush();
        }
        $produit->setArtist($artist);

        // Catégorie
        $categoryName = $request->request->get('categorie');
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            $category = new Category();
            $category->setName($categoryName);
            $em->persist($category);
            $em->flush(); // pour avoir un ID valide
        }

        $produit->setCategory($category);


        // Image
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $produit->setImage('uploads/' . $newFilename);
            } catch (FileException $e) {
                // gérer l'erreur (facultatif)
            }
        }

        $em->flush();

        return $this->redirectToRoute('admin_produit_index');
    }

    return $this->render('admin/produits/edit.html.twig', [
        'produit' => $produit,
    ]);
}



    




    

}
