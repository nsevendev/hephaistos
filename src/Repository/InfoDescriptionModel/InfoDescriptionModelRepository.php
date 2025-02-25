<?php

namespace Heph\Repository\InfoDescriptionModel;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;

/**
 * @extends ServiceEntityRepository<InfoDescriptionModel>
 */
class InfoDescriptionModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoDescriptionModel::class);
    }

    /*
    public function remove(string $id): void
    {
        $infoDescriptionModel = $this->find($id);
        if (null !== $infoDescriptionModel) {
            $this->getEntityManager()->remove($infoDescriptionModel);
            $this->save($infoDescriptionModel);
        }
    }
    */
}
