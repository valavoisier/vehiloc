<?php

namespace App\Controller;

use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();
        return $this->render('home/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    #[Route('/voiture/{id}', name: 'app_voiture_detail')]
    public function detail(int $id, VoitureRepository $voitureRepository): Response
    {
        $voiture = $voitureRepository->find($id);
        
        if (!$voiture) {
            throw $this->createNotFoundException('La voiture demandée n\'existe pas.');
        }
        
        return $this->render('home/detail.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/voiture/{id}/supprimer', name: 'app_voiture_delete')]
    public function delete(int $id, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {
        $voiture = $voitureRepository->find($id);
        
        if (!$voiture) {
            return $this->redirectToRoute('app_home');
        }
        
        $entityManager->remove($voiture);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_home');
    }
}
