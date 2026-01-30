<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Retourne uniquement les articles où published = true
     * @return Article[]
     */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.published = :val')
            ->setParameter('val', true)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les 10 derniers articles créés
     * @return Article[]
     */
    public function findRecent(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve tous les articles d'une catégorie spécifique
     * @return Article[]
     */
    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c') // On joint la table Category (alias c)
            ->addSelect('c')              // On sélectionne les données de la catégorie (Performance N+1)
            ->where('c.id = :id')         // On filtre par ID de catégorie
            ->setParameter('id', $categoryId)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve tous les articles écrits par un auteur spécifique
     * @return Article[]
     */
    public function findByAuthor(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.author', 'u')   
            ->addSelect('u')              
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function search(array $criteria): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            // --- OPTIMISATION N+1 ---
            // On joint et sélectionne les données liées (Catégorie + Auteur)
            // dès le début. Doctrine n'aura pas besoin de refaire des requêtes plus tard.
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->leftJoin('a.author', 'u')
            ->addSelect('u');

        // 1. Recherche par Titre
        if (!empty($criteria['title'])) {
            $qb->andWhere('a.title LIKE :title')
               ->setParameter('title', '%' . $criteria['title'] . '%');
        }

        // 2. Filtre par Catégorie
        if (!empty($criteria['category'])) {
            // Note : On utilise l'alias 'c' qui est déjà défini au tout début (ligne 7)
            $qb->andWhere('c.id = :catId')
               ->setParameter('catId', $criteria['category']);
        }

        // 3. LE TRI
        $direction = 'DESC';
        
        // CORRECTION IMPORTANTE :
        // Ton Controller envoie la clé 'sort' (regarde ton code précédent : 'sort' => $tri)
        // Donc ici, on doit vérifier $criteria['sort'], et pas 'tri'.
        if (isset($criteria['sort']) && strtoupper($criteria['sort']) === 'ASC') {
            $direction = 'ASC';
        }

        $qb->orderBy('a.createdAt', $direction);

        return $qb;
    }
}