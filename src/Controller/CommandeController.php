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

        

        $this->addFlash('success', 'Commande validée avec succès.');

        return $this->render('commande/index.html.twig', [
            'items' => $items,
            'total' => $panierService->getTotal(),
            'commande' => $commande,
        ]);
    }
}
