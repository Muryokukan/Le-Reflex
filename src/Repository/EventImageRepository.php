<?php

namespace App\Repository;

use App\Entity\EventImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventImage>
 */
class EventImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventImage::class);
    }

    public function findEnabledOrderedByDisplayOrder(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.enabled = :enabled')
            ->andWhere('e.fileName IS NOT NULL')
            ->setParameter('enabled', true)
            ->addSelect('CASE WHEN e.displayOrder IS NULL THEN 1 ELSE 0 END AS HIDDEN orderPriority')
            ->orderBy('orderPriority', 'ASC')
            ->addOrderBy('e.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
