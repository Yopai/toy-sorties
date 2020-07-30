<?php

namespace App\Repository;

use App\Entity\ExternalMember;
use App\Entity\ExternalSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExternalMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalMember[]    findAll()
 * @method ExternalMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalMember::class);
    }

    // /**
    //  * @return ExternalMember[] Returns an array of ExternalMember objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExternalMember
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findOneOrCreateByUsername(ExternalSource $source, string $username)
    {
        $result = $this->findOneBy(['source' => $source, 'username' => $username]);
        if (!$result) {
            $result = new ExternalMember();
            $result->setUsername($username);
            $result->setPassword('');
            $result->setEmail($username);
            $this->getEntityManager()->persist($result);
        }
        return $result;
    }
}
