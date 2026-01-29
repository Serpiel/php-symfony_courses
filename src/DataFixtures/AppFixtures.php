<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // On initialise Faker en français
        $faker = Factory::create('fr_FR');

        // 1. Création des Users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            // On met un mot de passe bidon pour l'instant
            $user->setPassword('password'); 
            
            $manager->persist($user);
            $users[] = $user; // On garde l'user en mémoire pour l'assigner aux articles plus tard
        }

        // 2. Création des Catégories
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->word()); // Un mot au hasard
            
            $manager->persist($category);
            $categories[] = $category; // On garde la catégorie en mémoire
        }

        // 3. Création des Articles
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence(6)); // Titre de 6 mots
            $article->setContent($faker->paragraph(3)); // 3 paragraphes
            $article->setCreatedAt($faker->dateTimeBetween('-6 months')); // Date aléatoire
            $article->setPublished($faker->boolean(70)); // 70% de chance d'être publié
            
            // RELATIONS : On prend un User et une Catégorie au hasard dans nos listes
            $article->setAuthor($faker->randomElement($users));
            $article->setCategory($faker->randomElement($categories));

            $manager->persist($article);
        }

        // On envoie tout en base de données en une seule fois
        $manager->flush();
    }
}