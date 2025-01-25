<?php

namespace Heph\Repository\Ping;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\Ping\PingEntity;

/**
 * @extends ServiceEntityRepository<PingEntity>
 */
class PingEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PingEntity::class);
    }

    public function save(PingEntity $ping): void
    {
        $this->getEntityManager()->persist($ping);
        $this->getEntityManager()->flush();
    }
}
