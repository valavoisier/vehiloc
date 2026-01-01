<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();// Récupère toutes les voitures depuis la base de données
        return $this->render('home/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    #[Route('/voiture/ajouter', name: 'app_voiture_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);// Crée le formulaire basé sur VoitureType
        
        $form->handleRequest($request);// Traite la requête HTTP
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voiture);// Prépare l'entité pour l'insertion
            $entityManager->flush();// Exécute l'insertion
            
            return $this->redirectToRoute('app_voiture_detail', ['id' => $voiture->getId()]);// Redirige vers la page de détail de la voiture nouvellement créée
        }
        
        return $this->render('home/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/voiture/{id}', name: 'app_voiture_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, VoitureRepository $voitureRepository): Response
    {
        $voiture = $voitureRepository->find($id);// Récupère la voiture par son ID
        
        if (!$voiture) {
            throw $this->createNotFoundException('La voiture demandée n\'existe pas.');//createNotFoundException génère une exception 404 si la voiture n'est pas trouvée
        }
        
        return $this->render('home/detail.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/voiture/{id}/supprimer', name: 'app_voiture_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {
        $voiture = $voitureRepository->find($id);// Récupère la voiture par son ID
        
        if (!$voiture) {
            return $this->redirectToRoute('app_home');
        }
        
        $entityManager->remove($voiture);// Supprime la voiture de la base de données
        $entityManager->flush();// Exécute la suppression
        
        return $this->redirectToRoute('app_home');
    }
}
