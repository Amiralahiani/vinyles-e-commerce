<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        if ($this->getUser()) {//si cet utilisateur est déjà connecté
            // Redirection vers le tableau de bord approprié en fonction du rôle
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_dashboard');
            } else {
                return $this->redirectToRoute('client_dashboard');
            }
        }

        // Affichage du formulaire
        //pour  faciliter la reconnexion de l'utilisateur
        $error = $authenticationUtils->getLastAuthenticationError();//recuperer la derniere erreur de connexion
        $lastUsername = $authenticationUtils->getLastUsername();//recuperer me dernier username saisi

        return $this->render('security/login.html.twig', [//rediriger vers la page de connexion
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    #[Route(path:'/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');//c juste un message de rappel  qu'on ne doit pas mettre de logique ici
    }
//symfony gère la deconnexion via le firewall pas via le controleur 
//la redirection se fait selon la configuration du firewall dans le fichier security.yaml
//cette methode doit exister pour que la route /logout existait





    
}
