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
            ->leftJoin('a.author', 'u')   // On joint la table User (alias u pour l'auteur)
            ->addSelect('u')              // On récupère les infos de l'auteur direct
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}