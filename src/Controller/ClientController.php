<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ClientController extends AbstractController
{
    #[Route('/client', name: 'client_dashboard')]
    #[IsGranted('ROLE_USER')]
public function index(): Response
{
    return $this->render('client/index.html.twig');
}

}
