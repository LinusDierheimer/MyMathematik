<?php

namespace App\Repository;

use App\Entity\PendingPasswordReset;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PendingPasswordReset|null find($id, $lockMode = null, $lockVersion = null)
 * @method PendingPasswordReset|null findOneBy(array $criteria, array $orderBy = null)
 * @method PendingPasswordReset[]    findAll()
 * @method PendingPasswordReset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PendingPasswordResetRepository extends AbstractModifiableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PendingPasswordReset::class);
    }

    // /**
    //  * @return PendingPasswordReset[] Returns an array of PendingPasswordReset objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByUserId($userId): ?PendingPasswordReset
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user_id = :val')
            ->setParameter('val', $userId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
