<?php

namespace App\Repository;

use App\Entity\OutingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OutingCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutingCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutingCategory[]    findAll()
 * @method OutingCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutingCategory::class);
    }

    // /**
    //  * @return OutingCategory[] Returns an array of OutingCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutingCategory
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
