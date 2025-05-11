<?php

namespace App\Controller;

use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
   

    #[Route('/panier', name: 'panier_index')]
    public function index(PanierService $panierService): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' => $panierService->getPanierWithData(),
            'total' => $panierService->getTotal()
        ]);
    }

    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function add(int $id, PanierService $panierService): Response
    {
        $panierService->add($id);
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/remove/{id}', name: 'panier_delete')]
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
    #[Route('/panier/update/{id}', name: 'panier_update', methods: ['POST'])]
    public function update(Request $request, int $id, PanierService $panierService): Response
    {
        $quantity = (int)$request->request->get('quantity', 1);
        $panierService->updateQuantity($id, $quantity);

        return $this->redirectToRoute('panier_index');
    }

}
