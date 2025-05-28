<?php

namespace App\Controller;

use App\Service\PanierService;
use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;


final class CommandeController extends AbstractController
{
    #[Route('/admin/commandes', name: 'admin_commande_index')]
    public function index(CommandeRepository $commandeRepository): Response
    {
    $commandes = $commandeRepository->findAllWithDetails();
    
    return $this->render('admin/commandes/index.html.twig', [
        'commandes' => $commandes,
    ]);
    }
    #[Route('/admin/commandes/{id}', name: 'admin_commande_show')]
    public function showCommande(Commande $commande): Response
    {
    return $this->render('admin/commandes/show.html.twig', [
        'commande' => $commande,
    ]);
    }
    #[Route('/commande/validate', name: 'app_commande')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function validate(PanierService $panierService, EntityManagerInterface $em): Response
    {
    // Vérifie que le panier n'est pas vide
    $items = $panierService->getpanierWithData();
    if (empty($items)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('panier_index');
    }

    $user = $this->getUser();
    if (!$user) {
        throw $this->createAccessDeniedException("Vous devez être connecté pour valider une commande.");
    }

    // Créer une commande
    $commande = new Commande();
    $commande->setUser($this->getUser());
    $commande->setCreatedAt(new \DateTimeImmutable());

    $em->persist($commande);

    // Créer les CommandeProduit à partir du panier
    foreach ($items as $item) {
        $commandeProduit = new CommandeProduit();
        $commandeProduit->setCommande($commande);
        $commandeProduit->setProduit($item['produit']);
        $commandeProduit->setQuantite($item['quantity']);
        $commandeProduit->setPrix($item['produit']->getPrice());

        $em->persist($commandeProduit);
    }

    $em->flush();
    
    // Vider le panier après validation de la commande
    $panierService->viderPanier();

    $this->addFlash('success', 'Commande validée avec succès.');

    // Renvoyer vers la nouvelle template de confirmation
    return $this->render('commande/confirmation_commande.html.twig', [
        'commande' => $commande,
        'items' => $items,
        'total' => $panierService->getTotal(),
    ]);
}
}
