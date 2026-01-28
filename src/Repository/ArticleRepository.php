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

}