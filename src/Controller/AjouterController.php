<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Memberships;
use App\Form\AddToClubType;
use App\Repository\ClubsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTime;

class AjouterController extends AbstractController
{
    #[Route('/ajouter', name: 'ajouter')]
    public function join(): Response
    {
        $memb = new Memberships();
        $form = $this->createForm(AddToClubType::class, $memb);

        return $this->render('ajouter/index.html.twig', [
            'controller_name' => 'AjouterController',
            'pageTitle' => 'Add Club Member',
            'activePage' => 'ajouter',
            'Aform' => $form->createView(),
        ]);
    }

    #[Route('/ajouter/add', name: 'add_membership', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        ClubsRepository $clubsRepository
    ): Response {
        $memb = new Memberships();
        $form = $this->createForm(AddToClubType::class, $memb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            if (!$user instanceof Etudiant) {
                return $this->redirectToRoute('signin');
            }

            $clubId = $form->get('club')->getData();
            $club = $clubsRepository->find($clubId);

            if ($club === null) {
                $this->addFlash('error', 'Selected club does not exist.');

                return $this->redirectToRoute('ajouter');
            }

            $memb->setUser($user);
            $memb->setClub($club);
            $memb->setJoinedAt(new DateTime());
            $entityManager->persist($memb);
            $entityManager->flush();

            $this->addFlash('success', 'Membership created successfully.');

            return $this->redirectToRoute('home');
        }

        return $this->render('ajouter/index.html.twig', [
            'controller_name' => 'AjouterController',
            'pageTitle' => 'Add Club Member',
            'activePage' => 'ajouter',
            'Aform' => $form->createView(),
        ]);
    }
    //#[Route('/ajouter', name: 'ajouter_post', methods: ['POST'])]
    //public function join_post(Request $request, EntityManagerInterface $em): Response
    ////{
      //$club = $request->request->get("club");
      //$role = $request->request->get("role");
//
      //$member = new Memberships();
      //$member->setUser($this->getUser());
      //$member->setClub($club);
      //$member->setRole($role);
//
      //$em->persist($member);
      //$em->flush();
    //}
}
