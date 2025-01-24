<?php

namespace Heph\Repository\InfoDescriptionModel;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModelEntity;

/**
 * @extends ServiceEntityRepository<InfoDescriptionModelEntity>
 */
class InfoDescriptionModelEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoDescriptionModelEntity::class);
    }

}
