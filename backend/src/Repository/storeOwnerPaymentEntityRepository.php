<?php

namespace App\Repository;

use App\Entity\StoreOwnerPaymentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StoreOwnerPaymentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreOwnerPaymentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreOwnerPaymentEntity[]    findAll()
 * @method StoreOwnerPaymentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreOwnerPaymentEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreOwnerPaymentEntity::class);
    }

    public function getpaymentsForOwner($ownerId)
    {
        return $this->createQueryBuilder('Payments')
               ->select('Payments.id, Payments.amount, Payments.date')

               ->andWhere('Payments.ownerId = :ownerId')

               ->setParameter('ownerId', $ownerId)

               ->getQuery()
               ->getResult();
    }
    
    public function getSumAmount($ownerId)
    {
        return $this->createQueryBuilder('Payments')
               ->select('sum(Payments.amount) as sumPayments')
               ->andWhere('Payments.ownerId = :ownerId')

               ->setParameter('ownerId', $ownerId)

               ->getQuery()
               ->getResult();
    }

    public function getNewAmount($ownerId)
    {
        return $this->createQueryBuilder('Payments')
               ->select('Payments.id, Payments.date')
               ->andWhere('Payments.ownerId = :ownerId')

               ->addGroupBy('Payments.id')
               ->setMaxResults(1)
               ->addOrderBy('Payments.id','DESC')

               ->setParameter('ownerId', $ownerId)

               ->getQuery()
               ->getResult();
    }
}
