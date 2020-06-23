<?php

namespace App\Repository;

use App\Entity\ExternalSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExternalSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalSource[]    findAll()
 * @method ExternalSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalSource::class);
    }

    public function findActiveIn($ids)
    {
        if (!$ids) {
            return [];
        }
        $qb = $this->createQueryBuilder('e');
        $query = $qb
            ->andWhere($qb->expr()->in('e.id', $ids))
            ->andWhere('e.active = true')
            ->getQuery();
        dump($query->getDQL());
        dump($query->getSQL());
        return $query->getResult();
    }
}
