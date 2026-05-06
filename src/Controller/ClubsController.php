<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clubs')]
final class ClubsController extends AbstractController
{
    #[Route('/acm', name: 'acm')]
    public function acm(): Response
    {
        return $this->render('acm/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'ACM',
        ]);
    }
    #[Route('/aero', name: 'aero')]
    public function aero(): Response
    {
        return $this->render('aero/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'Aerobotix',
        ]);
    }
    #[Route('/ieee', name: 'ieee')]
    public function ieee(): Response
    {
        return $this->render('ieee/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'IEEE',
        ]);
    }
    #[Route('/theatro', name: 'theatro')]
    public function theatro(): Response
    {
        return $this->render('theatro/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'Theatro',
        ]);
    }
    #[Route('/securinets', name: 'secu')]
    public function secu(): Response
    {
        return $this->render('secu/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'Securinets',
        ]);
    }
    #[Route('/cim', name: 'cim')]
    public function cim(): Response
    {
        return $this->render('cim/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'CIM',
        ]);
    }
    #[Route('/insat-press', name: 'press')]
    public function press(): Response
    {
        return $this->render('press/index.html.twig', [
            'controller_name' => 'ClubsController',
            'activePage' => 'clubs',
            'pageTitle' => 'Press',
        ]);
    }
}
