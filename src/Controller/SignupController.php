<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SignupFormType;

final class SignupController extends AbstractController
{
    #[Route('/signup', name: 'signup')]
    public function index(): Response
    {
        $form = $this->createForm(SignupFormType::class);

        return $this->render('signup/index.html.twig', [
            'controller_name' => 'SignupController',
            'pageTitle' => 'Sign Up',
            'activePage' => 'signup',
            'Sform' => $form->createView()
        ]);
    }
}
