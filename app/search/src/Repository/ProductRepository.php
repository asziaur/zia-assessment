<?php

// src/Repository/ProductRepository.php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Search products based on filters and sort.
     */
    public function searchWithPagination(array $filters, string $sort, int $page, int $perPage)
    {
        $qb = $this->createQueryBuilder('p');

        // Apply filters to the query
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $qb->andWhere("p.$field LIKE :$field")
                    ->setParameter($field, "%$value%"); // Use LIKE for partial matches
            }
        }

        // Apply sorting
        $qb->orderBy("p.$sort", 'ASC')
           ->setFirstResult(($page - 1) * $perPage)
           ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    /**
     * Count the products based on filters.
     */
    public function countFiltered(array $filters): int
    {
        $qb = $this->createQueryBuilder('p');

        // Apply filters to the query
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $qb->andWhere("p.$field LIKE :$field")
                    ->setParameter($field, "%$value%");
            }
        }

        return (int)$qb->select('COUNT(p.id)')
                       ->getQuery()
                       ->getSingleScalarResult();
    }

    public function search(array $filters, string $sort = 'id'): array
    {
        $qb = $this->createQueryBuilder('p');

        // Apply filters
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $qb->andWhere("p.$field LIKE :$field")
                   ->setParameter($field, "%$value%");
            }
        }

        // Apply sorting
        $qb->orderBy("p.$sort", 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
