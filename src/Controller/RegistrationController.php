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
{/*
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }*/

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
            //me diriger vers lapage de registration
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form,
            ]);
        }

        // Always return a response if form is not submitted or not valid
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
/*
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('malek.azri@insat.ucar.tn', 'Retro commerce'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_produit_index');
        }*/
    
/*
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }*/

    }
