<?php

namespace Heph\Repository\Ping;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\Ping\Ping;

/**
 * @extends ServiceEntityRepository<Ping>
 */
class PingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ping::class);
    }

    public function save(Ping $ping): void
    {
        $this->getEntityManager()->persist($ping);
        $this->getEntityManager()->flush();
    }

    public function remove(Ping $ping): void
    {
        $this->getEntityManager()->remove($ping);
        $this->getEntityManager()->flush();
    }
}
