<?php

namespace App\Repository;

use App\Entity\UploadedVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedVideo[]    findAll()
 * @method UploadedVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedVideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedVideo::class);
    }

    // /**
    //  * @return UploadedVideo[] Returns an array of UploadedVideo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UploadedVideo
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
