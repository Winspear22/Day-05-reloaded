<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function getPersonsGrouped(
            string $filterName = '',
            string $sortBy = 'name',
            string $sortDir = 'asc'
        ): array
        {
            $allowedSorts = ['name', 'email', 'birthdate'];
            $allowedDir = ['asc', 'desc'];

            if (!in_array($sortBy, $allowedSorts, true))
                $sortBy = 'name';

            if (!in_array($sortDir, $allowedDir, true))
                $sortDir = 'asc';

            $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.addresses', 'a')->addSelect('a')
                ->leftJoin('p.bankAccount', 'b')->addSelect('b');

            if (!empty(trim($filterName)))
            {
                $qb->andWhere('p.name LIKE :filter')
                ->setParameter('filter', "%{$filterName}%");
            }

            $qb->orderBy("p.{$sortBy}", strtoupper($sortDir))
            ->addOrderBy('p.id', 'ASC');

            return $qb->getQuery()->getResult();
    }
}
