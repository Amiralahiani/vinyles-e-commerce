<?php

namespace App\Controller;
use App\Repository\ContactMessageRepository;

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
//passer la somme des stocks de produits, le nombre de commandes et le nombre d'utilisateurs à la vue admin
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
    
    // src/Controller/AdminController.php

    // src/Controller/AdminController.php

#[Route('/admin/commandes', name: 'admin_commande_index')]
public function commandes(CommandeRepository $commandeRepository): Response
{
    $commandes = $commandeRepository->findAllWithDetails(); // Utilisez votre méthode personnalisée
    
    return $this->render('admin/commandes/index.html.twig', [
        'commandes' => $commandes,
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
        if (!$category) {//si la categorie qu'on souhaite ajouter n'existe pas on la crée
            $category = new Category();
            $category->setName($categoryName);
            $em->persist($category);
        }

        // Gestion de l'artiste
        $artistName = $form->get('artist_name')->getData();
        $artist = $artistRepository->findOneBy(['name' => $artistName]);
        if (!$artist) {//si l'artiste qu'on souhaite ajouter n'existe pas on le crée
            $artist = new Artist();
            $artist->setName($artistName);
            $em->persist($artist);
        }

        $produit->setCategory($category);
        $produit->setArtist($artist);

        // Gestion de l'image
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {//si l'utilisateur a ajouté une image
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);//pour securiser le nom de fichier s'il contient des chemins ou des caractères spéciaux par exemple
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
//uniqid va generer un id pour le fichier à partir du timestamp actuel, ce qui garantit que le nom de fichier est unique
//guessExtension va deviner l'extension du fichier à partir de son type MIME
            try {
                $imageFile->move(
                    $this->getParameter('uploads_directory'), //uploads_directory est un paramètre défini dans config/services.yaml pour spécifier le répertoire de destination où les fichiers seront stockés
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
        if (!$artist && $artistName !== '') {//si l'artiste n'existe pas et que le nom de l'artiste n'est pas vide
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
            $em->flush(); 
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

#[Route('/admin/messages', name: 'admin_messages')]
public function showMessages(ContactMessageRepository $repo): Response
{
    $messages = $repo->findAll();

    return $this->render('admin/messages.html.twig', [
        'messages' => $messages,
    ]);
}

}



    




    


