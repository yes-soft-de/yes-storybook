<?php

namespace App\Repository;

use App\Entity\CaptainPaymentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CaptainPaymentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaptainPaymentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaptainPaymentEntity[]    findAll()
 * @method CaptainPaymentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaptainPaymentEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaptainPaymentEntity::class);
    }

    public function  getpayments($captainId)
    {
        return $this->createQueryBuilder('PaymentsCaptain')
               ->select('PaymentsCaptain.id, PaymentsCaptain.captainId, PaymentsCaptain.amount, PaymentsCaptain.date')

               ->andWhere('PaymentsCaptain.captainId = :captainId')

               ->setParameter('captainId', $captainId)

               ->getQuery()
               ->getResult();
    }
    
    public function getSumAmount($captainId)
    {
        return $this->createQueryBuilder('PaymentsCaptain')
               ->select('sum(PaymentsCaptain.amount) as sumPayments')
               ->andWhere('PaymentsCaptain.captainId = :captainId')

               ->setParameter('captainId', $captainId)

               ->getQuery()
               ->getResult();
    }
}
