<?php

namespace App\Controller;

use App\Entity\TeamMember;
use App\Form\TeamMemberType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamMemberController extends AbstractController
{
    #[Route('/team-members', name: 'team_members', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('team-members/index.html.twig');
    }

    #[Route('/team-members/hire', name: 'team_members_hire', methods: ['GET', 'POST'])]
    public function hire(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TeamMemberType::class, new TeamMember());
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $doctrine->getManager();
            $teamMember = $form->getData();
            $entityManager->persist($teamMember);
            $entityManager->flush();
            
            return $this->redirectToRoute('team_members');
        }
        
        return $this->renderForm('team-members/hire.html.twig', [
            'form'=>$form
        ]);
    }
}