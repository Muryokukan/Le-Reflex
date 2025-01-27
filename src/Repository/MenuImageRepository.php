<?php

namespace App\Repository;

use App\Entity\MenuImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuImage>
 */
class MenuImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuImage::class);
    }

    public function findEnabledOrderedByDisplayOrder(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.enabled = :enabled')
            ->andWhere('m.fileName IS NOT NULL')
            ->setParameter('enabled', true)
            ->addSelect('CASE WHEN m.displayOrder IS NULL THEN 1 ELSE 0 END AS HIDDEN orderPriority')
            ->orderBy('orderPriority', 'ASC')
            ->addOrderBy('m.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
