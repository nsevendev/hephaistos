<?php

namespace Heph\Repository\LmQuatre;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\LmQuatre\LmQuatre;

/**
 * @extends ServiceEntityRepository<LmQuatre>
 */
class LmQuatreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LmQuatre::class);
    }
}
