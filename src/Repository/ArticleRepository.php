<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ArticleRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Article::class);
    }

    /**
     * Finds articles published before the current date and time.
     *
     * @return Article[] Returns an array of Article objects
     */
    public function findPublishedBeforeNow(): array {
        return $this->createQueryBuilder('a')
            ->andWhere('a.publicationDate <= :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    
}
