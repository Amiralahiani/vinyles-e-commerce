<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactMessageController extends AbstractController
{
    #[Route('/contact/message', name: 'app_contact_message', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
{
    if ($request->isMethod('POST')) {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le contenu du message soumis
        $content = trim($request->request->get('message', ''));

        // Vérifier que le message n'est pas vide
        if ($content === '') {
            $this->addFlash('error', 'Le message ne peut pas être vide.');

            // recharger le formulaire avec une erreur
            return $this->render('contact_message/index.html.twig', [
                'controller_name' => 'ContactMessageController',
            ]);
        }

        /** @var \App\Entity\User $user */
        $email = $user->getEmail();

        $message = new ContactMessage();
        $message->setEmail($email);
        $message->setContent($content);
        $message->setCreatedAt(new \DateTimeImmutable());

        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('app_contact_thank_you');
    }

    return $this->render('contact_message/index.html.twig', [
        'controller_name' => 'ContactMessageController',
    ]);
}
#[Route('/contact/merci', name: 'app_contact_thank_you', methods: ['GET'])]
public function thankYou(): Response
{
    return $this->render('contact_message/thank_you.html.twig');
}



}
