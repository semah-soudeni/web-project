<?php

namespace App\Controller;

use App\Entity\Memberships;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AjouterController extends AbstractController
{
    #[Route('/ajouter', name: 'ajouter')]
    public function join(): Response
    {
        return $this->render('ajouter/index.html.twig', [
            'controller_name' => 'AjouterController',
            'pageTitle' => 'Add Club Member',
            'activePage' => 'ajouter',
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
