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
    public function show(ManagerRegistry $doctrine): Response
    {   
        $teamMembers = $doctrine->getRepository(TeamMember::class)->findAll();
        return $this->render('team-members/show.html.twig', [
            'teamMembers' => $teamMembers
        ]);
    }

    #[Route('/team-members/hire', name: 'team_members_hire', methods: ['GET', 'POST'])]
    public function hire(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(
            TeamMemberType::class, 
            new TeamMember(), 
            [
                'submit_button_label' => 'Hire'
            ]
        );
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
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

    #[Route('/team-members/{id}/edit', name: 'team_members_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $teamMember = $doctrine->getRepository(TeamMember::class)->find($id);
        if(!isset($teamMember)){
            return $this->redirectToRoute('team_members');
        }

        $form = $this->createForm(
            TeamMemberType::class, 
            $teamMember, 
            [
                'submit_button_label' => 'Edit'
            ]
        );
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('team_members');
        }
        
        return $this->renderForm('team-members/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/team-members/{id}/delete', name: 'team_members_delete', methods: ['GET'])]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {   
        $entityManager = $doctrine->getManager();
        $teamMember = $doctrine->getRepository(TeamMember::class)->find($id);
        if(isset($teamMember))
        {
            $entityManager->remove($teamMember);
            $entityManager->flush();
        }

        return $this->redirectToRoute('team_members');
    }
}