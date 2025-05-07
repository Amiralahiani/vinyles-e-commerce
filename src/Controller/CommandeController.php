<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    #[Route('/order/validate', name: 'order_validate')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function validate(PanierService $panierService, EntityManagerInterface $em): Response
    {
        // Vérifie que le panier n'est pas vide
        $items = $cartService->getCartWithData();
        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
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
            $commandeProduit->setProduct($item['product']);
            $commandeProduit->setQuantity($item['quantity']);
            $commandeProduit->setPrice($item['product']->getPrice());

            $em->persist($commandeProduit);
        }

        $em->flush();

        // Vider le panier
        $panierService->clear();

        $this->addFlash('success', 'Commande validée avec succès.');

        return $this->redirectToRoute('panier_index');
    }
}
