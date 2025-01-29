<?php

namespace Heph\Repository\CarSales;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\CarSales\CarSales;

/**
 * @extends ServiceEntityRepository<CarSales>
 */
class CarSalesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarSales::class);
    }
}
