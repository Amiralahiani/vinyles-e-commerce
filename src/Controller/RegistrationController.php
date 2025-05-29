<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
   

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);//lier le formulaire d'inscription à l'entité User
        $form->handleRequest($request);//recuperer les données du formulaire
        $user->setRoles(['ROLE_CLIENT']);//le nom de rôle par défaut pour les nouveaux utilisateurs est 'ROLE_CLIENT'


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();// récupérer le mot de passe en clair saisi par l'utilisateur

            //hash du mdp et assignation à l'utilisateur
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            // ajouter l'utilisateur à la base de données
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit_index');

           
        }

            


            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form,
            ]);        }

    }

    

