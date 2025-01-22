<?php

namespace App\Repository;

use App\Entity\RoomReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomReservation>
 */
class RoomReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomReservation::class);
    }

    public function countReservationsByStatus(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('r.status, COUNT(r.id) as count')
            ->groupBy('r.status')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $result) {
            $status = $result['status']->value;
            $formattedResults[$status] = (int) $result['count'];
        }

        return $formattedResults;
    }
}
