<?php

namespace Heph\Repository\CarSales;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\CarSales\CarSalesEntity;

/**
 * @extends ServiceEntityRepository<CarSalesEntity>
 */
class CarSalesEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarSalesEntity::class);
    }
}
