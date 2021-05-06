<?php

namespace App\Repository;

use App\Entity\StoreOwnerProfileEntity;
use App\Entity\StoreOwnerBranchEntity;
use App\Entity\OrderEntity;
use App\Entity\CaptainProfileEntity;
use App\Entity\AcceptedOrderEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method StoreOwnerProfileEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreOwnerProfileEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreOwnerProfileEntity[]    findAll()
 * @method StoreOwnerProfileEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreOwnerProfileEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreOwnerProfileEntity::class);
    }

    public function getUserProfileByUserID($userID)
    {
        return $this->createQueryBuilder('profile')

            ->select('profile.id', 'profile.userName','profile.userID', 'profile.image', 'profile.story',
                'profile.branch', 'profile.free', 'profile.status', 'profile.city', 'profile.phone', 'profile.image')
            ->andWhere('profile.userID=:userID')
            ->setParameter('userID', $userID)

            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUserProfileByID($id)
    {
        return $this->createQueryBuilder('profile')
            ->select('profile.id', 'profile.userName','profile.userID', 'profile.image', 'profile.story', 'profile.branch', 'profile.free', 'profile.status', 'profile.phone')

            ->andWhere('profile.id = :id')

            ->setParameter('id', $id)

            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUserProfile($userID)
    {
        return $this->createQueryBuilder('profile')

            ->andWhere('profile.userID = :userID')
            ->setParameter('userID', $userID)

            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getOwners()
    {
        return $this->createQueryBuilder('profile')

            ->select('profile.id', 'profile.userID', 'profile.userName', 'profile.image', 'profile.story', 'profile.free', 'profile.branch as branchcount')
            ->addSelect('orderEntity.id as orderID', 'orderEntity.date', 'orderEntity.source', 'orderEntity.fromBranch', 'orderEntity.payment', 'orderEntity.destination','branchesEntity.location','branchesEntity.brancheName','branchesEntity.city as branchCity', 'acceptedOrderEntity.captainID','captainProfileEntity.name as captainName')
       
            ->leftJoin(OrderEntity::class, 'orderEntity', Join::WITH, 'profile.userID = orderEntity.ownerID')

            ->leftJoin(StoreOwnerBranchEntity::class, 'branchesEntity', Join::WITH, 'orderEntity.fromBranch = branchesEntity.id')

            ->leftJoin(AcceptedOrderEntity::class, 'acceptedOrderEntity', Join::WITH, 'orderEntity.id = acceptedOrderEntity.orderID')

            ->leftJoin(CaptainProfileEntity::class, 'captainProfileEntity', Join::WITH, 'acceptedOrderEntity.captainID = captainProfileEntity.captainID')

            ->getQuery()
            ->getResult();
    }

    public function getAllStoreOwners()
    {
        return $this->createQueryBuilder('profile')

            ->select('profile.id', 'profile.userID', 'profile.userName', 'profile.free', 'profile.branch', 'profile.uuid')

            ->getQuery()
            ->getResult();
    }
}
