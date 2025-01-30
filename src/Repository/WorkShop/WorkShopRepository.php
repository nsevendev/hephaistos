<?php

namespace Heph\Repository\WorkShop;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\WorkShop\WorkShop;

/**
 * @extends ServiceEntityRepository<WorkShop>
 */
class WorkShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkShop::class);
    }
}
