<?php

namespace Heph\Repository\TermsConditions;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\TermsConditions\TermsConditions;

/**
 * @extends ServiceEntityRepository<TermsConditions>
 */
class TermsConditionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermsConditions::class);
    }

    public function save(TermsConditions $termsConditions): void
    {
        $this->getEntityManager()->persist($termsConditions);
        $this->getEntityManager()->flush();
    }

    public function findAllWithArticles(): array
    {
        return $this->createQueryBuilder('tc')
            ->leftJoin('tc.listTermsConditionsArticle', 'article')
            ->addSelect('article')
            ->orderBy('tc.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
