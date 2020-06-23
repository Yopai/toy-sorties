<?php

namespace App\Repository;

use App\Entity\ExternalOuting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExternalOuting|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalOuting|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalOuting[]    findAll()
 * @method ExternalOuting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalOutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalOuting::class);
    }

    public function findOrCreate($source, $id)
    {
        $result = $this->findOneBy(['source' => $source, 'externalId' => $id]);
        if ( ! $result) {
            $result = new ExternalOuting();
            $result->setSource($source);
            $result->setExternalId($id);
        }

        return $result;
    }

    public function deleteAll()
    {
        $query = $this->createQueryBuilder('e')
            ->delete()
            ->getQuery();

        return $query->execute();
    }
}
