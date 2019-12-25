<?php

namespace App\Repository;

use App\Entity\PendingEmailVerification;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PendingEmailVerification|null find($id, $lockMode = null, $lockVersion = null)
 * @method PendingEmailVerification|null findOneBy(array $criteria, array $orderBy = null)
 * @method PendingEmailVerification[]    findAll()
 * @method PendingEmailVerification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PendingEmailVerificationRepository extends AbstractModifiableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PendingEmailVerification::class);
    }

    // /**
    //  * @return PendingEmailVerification[] Returns an array of PendingEmailVerification objects
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

    public function findOneByUserId($userId): ?PendingEmailVerification
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user_id = :val')
            ->setParameter('val', $userId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
