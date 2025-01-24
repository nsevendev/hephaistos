<?php

namespace Heph\Repository\LmQuatre;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\LmQuatre\LmQuatreEntity;

/**
 * @extends ServiceEntityRepository<LmQuatreEntity>
 */
class LmQuatreEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LmQuatreEntity::class);
    }
}
