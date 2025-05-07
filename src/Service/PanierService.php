<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use App\Entity\Product;

class PaniertService
{
    private SessionInterface $session;
    private ProductRepository $repo;

    public function __construct(RequestStack $requestStack, ProductRepository $repo)
    {
        $this->session = $requestStack->getSession();
        $this->repo = $repo;
    }

    public function add(int $id): void
    {
        $cart = $this->session->get('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + 1;
        $this->session->set('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }

    public function getCartWithData(): array
    {
        $cart = $this->session->get('cart', []);
        $data = [];

        foreach ($cart as $id => $qty) {
            $product = $this->repo->find($id);
            if ($product instanceof Product) {
                $data[] = [
                    'product' => $product,
                    'quantity' => $qty,
                ];
            }
        }

        return $data;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getCartWithData() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }
}
