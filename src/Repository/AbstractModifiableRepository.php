<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractModifiableRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        $entityClass
    ) {
        parent::__construct($registry, $entityClass);
    }

    public function getEntityManager()
    {
        return parent::getEntityManager();
    }

    public function exist($entity)
    {
        $id = $entity->getId();

        return
            $id !== null &&
            $this->find($id) === null
        ;
    }

    public function flush()
    {
        $this->_em->flush();
    }

    public function persist($entity)
    {
        $this->_em->persist($entity);
    }

    public function remove($entity)
    {
        $this->_em->remove($entity);
    }

    public function save($entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    public function delete($entity)
    {
        $this->remove($entity);
        $this->flush();
    }

}