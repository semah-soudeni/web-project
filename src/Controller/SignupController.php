<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Enum\Role;
use App\Form\SignupFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

final class SignupController extends AbstractController
{
    #[Route('/signup', name: 'signup')]
    public function index(): Response
    {
        $etudiant = new Etudiant();

        $form = $this->createForm(SignupFormType::class, $etudiant);

        return $this->render('signup/index.html.twig', [
            'controller_name' => 'SignupController',
            'pageTitle' => 'Sign Up',
            'activePage' => 'signup',
            'Sform' => $form->createView(),
        ]);
    }

    #[Route('/signup/add', name: 'add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $loginFormAuthenticator
    ): Response
    {
        $etudiant = new Etudiant();
        $etudiant->setRole(Role::USER);

        $form = $this->createForm(SignupFormType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiant->setPassword($passwordHasher->hashPassword($etudiant, $etudiant->getPassword()));
            $entityManager->persist($etudiant);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser($etudiant, $loginFormAuthenticator, $request)
                ?? $this->redirectToRoute('home');
        }

        return $this->render('signup/index.html.twig', [
            'controller_name' => 'SignupController',
            'pageTitle' => 'Sign Up',
            'activePage' => 'signup',
            'Sform' => $form->createView(),
        ]);
    }
}
