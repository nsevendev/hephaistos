<?php

namespace Heph\Repository\EngineRemap;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\EngineRemap\EngineRemapEntity;

/**
 * @extends ServiceEntityRepository<EngineRemapEntity>
 */
class EngineRemapEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EngineRemapEntity::class);
    }
}
