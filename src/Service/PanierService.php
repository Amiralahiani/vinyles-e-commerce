<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;
use App\Entity\Produit;


class PanierService
{
    private SessionInterface $session;
    private ProduitRepository $repo;

    public function __construct(RequestStack $requestStack, ProduitRepository $repo)
    {
        $this->session = $requestStack->getSession();
        $this->repo = $repo;
    }

    public function add(int $id): void
    {
        $panier = $this->session->get('panier', []);
        $panier[$id] = ($panier[$id] ?? 0) + 1;
        $this->session->set('panier', $panier);
    }

    public function remove(int $id): void
    {
        $panier = $this->session->get('panier', []);
        unset($panier[$id]);
        $this->session->set('panier', $panier);
    }

    public function getPanierWithData(): array
    {
        $panier = $this->session->get('panier', []);
        $data = [];

        foreach ($panier as $id => $qty) {
            $produit = $this->repo->find($id);
            if ($produit instanceof Produit) {
                $data[] = [
                    'produit' => $produit,
                    'quantity' => $qty,
                ];
            }
        }

        return $data;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getPanierWithData() as $item) {
            $total += $item['produit']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    public function clear(): void
    {
        $this->session->remove('panier');
    }
    public function updateQuantity(int $id, int $quantity): void
    {
        $panier = $this->session->get('panier', []);

        if ($quantity <= 0) {
            unset($panier[$id]); // Supprime le produit si quantité <= 0
        } else {
            $panier[$id] = $quantity;
        }

        $this->session->set('panier', $panier);
    }
    // src/Service/PanierService.php

    public function viderPanier(): void
    {
        $this->session->remove('panier');
    }
}
