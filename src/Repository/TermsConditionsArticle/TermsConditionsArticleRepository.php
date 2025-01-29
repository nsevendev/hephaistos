<?php

namespace Heph\Repository\TermsConditionsArticle;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;

/**
 * @extends ServiceEntityRepository<TermsConditionsArticle>
 */
class TermsConditionsArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermsConditionsArticle::class);
    }

    public function save(TermsConditionsArticle $termsConditionsArticle): void
    {
        $this->getEntityManager()->persist($termsConditionsArticle);
        $this->getEntityManager()->flush();
    }

    public function remove(string $id): void
    {
        $termsConditionsArticle = $this->find($id);
        if (null !== $termsConditionsArticle) {
            $this->getEntityManager()->remove($termsConditionsArticle);
            $this->save($termsConditionsArticle);
        }
    }
}
