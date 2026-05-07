<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SigninFormType;

final class SigninController extends AbstractController
{
    #[Route('/signin', name: 'signin')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SigninFormType::class);

        return $this->render('signin/index.html.twig', [
            'controller_name' => 'SigninController',
            'Sform' => $form->createView(),
        ]);
    }
}
