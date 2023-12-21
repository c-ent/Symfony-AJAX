<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils,Security $security): Response
    {

        // get the login error if there is one
        if ($security->getUser()) {
            return $this->redirectToRoute('product_list');
        }
        // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();

         return $this->render('login/index.html.twig', [
                         'last_username' => $lastUsername,
                         'error'         => $error,
                      ]);
    }

    
}
