<?php

namespace Heph\Repository\EngineRemap;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\EngineRemap\EngineRemap;

/**
 * @extends ServiceEntityRepository<EngineRemap>
 */
class EngineRemapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EngineRemap::class);
    }

    public function findFirst(): ?EngineRemap
    {
        return $this->findOneBy([]);
    }
}
