<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitForm;
use App\Repository\ProduitRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, CategoryRepository $categoryRepository): Response
{
    return $this->render('produit/index.html.twig', [
        'produits' => $produitRepository->findAll(),
        'categories' => $categoryRepository->findAll(),
        'categorie_active' => null,
    ]);
}

    #[Route('/recherche', name: 'app_recherche')]
public function recherche(Request $request, ProduitRepository $produitRepository): Response
{
    $terme = $request->query->get('q');

    $produits = $produitRepository->createQueryBuilder('p')
        ->join('p.artist', 'a')
        ->where('a.name LIKE :terme')
        ->setParameter('terme', '%' . $terme . '%')
        ->getQuery()
        ->getResult();

    return $this->render('produit/index.html.twig', [
        'produits' => $produits,
        'terme_recherche' => $terme,
    ]);
}


    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/produits/categorie/{nom}', name: 'app_produit_par_categorie')]
    public function produitParCategorie(string $nom, ProduitRepository $produitRepository, CategoryRepository $categoryRepository): Response
{
    $produits = $produitRepository->createQueryBuilder('p')
        ->join('p.Category', 'c')
        ->where('c.name = :nom')
        ->setParameter('nom', $nom)
        ->getQuery()
        ->getResult();

    return $this->render('produit/index.html.twig', [
        'produits' => $produits,
        'categories' => $categoryRepository->findAll(),
        'categorie_active' => $nom,
    ]);
}
    

}
