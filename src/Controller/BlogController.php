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
                'title' => 'Maîtriser Twig',
                'content' => 'Twig est un moteur de template puissant. Les conditions et les boucles n\'auront plus de secret pour vous.',
                'image' => 'https://picsum.photos/seed/twig/400/250',
                'publishedAt' => new \DateTime('-10 days'), // Vieux de 10 jours -> Pas de badge
            ],
            [
                'id' => 3,
                'title' => 'Le CSS moderne',
                'content' => 'Flexbox et Grid ont changé la façon dont nous concevons les layouts sur le web. Fini les float !',
                'image' => 'https://picsum.photos/seed/css/400/250',
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