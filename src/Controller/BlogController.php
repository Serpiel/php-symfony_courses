<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        // 1. Simulation : L'utilisateur est-il admin ? (true ou false)
        // Utile pour l'exercice "Bouton Admin"
        $isAdmin = true;

        // 2. Simulation : Création de faux articles
        $articles = [
            [
                'id' => 1,
                'title' => 'Pourquoi le Metal est le meilleur genre musical ?',
                'content' => 'Guitare énervée, batterie qui tabasse, voix gutturales... Le metal offre une expérience sonore unique pour les amateurs de sensations fortes.',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Bmth.jpg/250px-Bmth.jpg',
                'publishedAt' => new \DateTime('now'), // Date d'aujourd'hui -> Devrait avoir le badge "Nouveau"
            ],
            [
                'id' => 2,
                'title' => 'Le phénomène SERPIEL fait parler de lui',
                'content' => 'Ce nouvel artiste qui est en roue libre',
                'image' => 'images/SERPIEL_logo.png',
                'publishedAt' => new \DateTime('-10 days'), // Vieux de 10 jours -> Pas de badge
            ],
            [
                'id' => 3,
                'title' => 'A bas la ICE',
                'content' => 'Les fascistes sont de retour',
                'image' => 'https://media.cnn.com/api/v1/images/stellar/prod/ap25027710663013.jpg?c=16x9&q=h_833,w_1480,c_fill',
                'publishedAt' => new \DateTime('-2 days'), // Vieux de 2 jours -> Devrait avoir le badge "Nouveau"
            ]
        ];

        // ASTUCE : Décommente la ligne ci-dessous pour tester le message "Aucun article" :
        // $articles = [];

        return $this->render('blog/index.html.twig', [
            // On envoie les données à la vue Twig
            'articles' => $articles,
            'isAdmin' => $isAdmin,
        ]);
    }
}