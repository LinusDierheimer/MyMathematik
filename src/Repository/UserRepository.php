<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractModifiableRepository
{

    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->setParameter('val', $email)
            ->andWhere('u.email = :val')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
