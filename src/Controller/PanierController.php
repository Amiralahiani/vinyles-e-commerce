<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
   

    #[Route('/panier', name: 'panier_index')]
    public function index(PanierService $panierService): Response
    {
        return $this->render('panier/index.html.twig', [
            'items' => $panierService->getPanierWithData(),
            'total' => $panierService->getTotal()
        ]);
    }

    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function add(int $id, PanierService $panierService): Response
    {
        $panierService->add($id);
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/remove/{id}', name: 'panier_remove')]
    public function remove(int $id, PanierService $panierService): Response
    {
        $panierService->remove($id);
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/clear', name: 'panier_clear')]
    public function clear(PanierService $panierService): Response
    {
        $panierService->clear();
        return $this->redirectToRoute('panier_index');
    }
}
