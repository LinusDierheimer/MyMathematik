<?php

namespace App\Repository;

use App\Entity\PostAnswer;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostAnswer[]    findAll()
 * @method PostAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostAnswerRepository extends AbstractModifiableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostAnswer::class);
    }

    // /**
    //  * @return PostAnswer[] Returns an array of PostAnswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostAnswer
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
