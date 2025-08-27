<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Movement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movement::class);
    }

    /**
     * @return Movement[]
     */
    public function findByFilter(?Category $category = null): array
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.category', 'c')->addSelect('c')
            ->orderBy('m.date', 'DESC')
            ->addOrderBy('m.id', 'DESC');

        if ($category) {
            $qb->andWhere('m.category = :cat')->setParameter('cat', $category);
        }

        return $qb->getQuery()->getResult();
    }
}
