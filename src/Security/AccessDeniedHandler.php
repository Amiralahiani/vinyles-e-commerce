<?php
namespace App\Security;

// src/Security/AccessDeniedHandler.php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;//injection de Twig dans le handler

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        // Rendre directement la vue Twig
        return new Response(
            $this->twig->render('security/access_denied.html.twig')
        );
    }
}