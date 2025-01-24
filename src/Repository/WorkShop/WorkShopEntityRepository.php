<?php

namespace Heph\Repository\WorkShop;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\WorkShop\WorkShopEntity;

/**
 * @extends ServiceEntityRepository<WorkShopEntity>
 */
class WorkShopEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkShopEntity::class);
    }
}
