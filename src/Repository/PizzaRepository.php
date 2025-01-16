<?php

namespace App\Repository;

use App\Entity\Pizza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pizza>
 *
 * @method Pizza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pizza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pizza[]    findAll()
 * @method Pizza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PizzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pizza::class);
    }

    /**
     * @return Pizza[] Returns an array of active Pizzas with their toppings
     */
    public function findAllWithToppings(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.toppings', 't')
            ->addSelect('t')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find a pizza by its ID with all toppings
     */
    public function findOneWithToppings(int $id): ?Pizza
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.toppings', 't')
            ->addSelect('t')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Pizza[] Returns an array of Pizzas matching the search
     */
    public function searchByName(string $term): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:term)')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Pizza[] Returns an array of Pizzas within a price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price >= :minPrice')
            ->andWhere('p.price <= :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(Pizza $pizza, bool $flush = false): void
    {
        $this->getEntityManager()->persist($pizza);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pizza $pizza, bool $flush = false): void
    {
        $this->getEntityManager()->remove($pizza);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}