<?php

namespace App\Repository;

use App\Entity\CaptainProfileEntity;
use App\Entity\AcceptedOrderEntity;
use App\Entity\StoreOwnerProfileEntity;
use App\Entity\OrderEntity;
use App\Entity\StoreOwnerBranchEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method CaptainProfileEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaptainProfileEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaptainProfileEntity[]    findAll()
 * @method CaptainProfileEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaptainProfileEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaptainProfileEntity::class);
    }

    public function getCaptainprofile($userID)
    {
        return $this->createQueryBuilder('captainProfile')

            ->andWhere('captainProfile.captainID=:userID')
            ->setParameter('userID', $userID)

            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getCaptainProfileByCaptainID($captainID)
    {
        return $this->createQueryBuilder('captainProfile')
            ->addSelect('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.state', 'captainProfile.specialLink', 'captainProfile.phone', 'captainProfile.isOnline', 'captainProfile.bankName', 'captainProfile.bankAccountNumber', 'captainProfile.stcPay')

            ->andWhere('captainProfile.captainID=:captainID')
            
            ->setParameter('captainID', $captainID)

            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getByCaptainIDForUpdate($captainID)
    {
        return $this->createQueryBuilder('captainProfile')

            ->andWhere('captainProfile.captainID = :captainID')
            ->setParameter('captainID', $captainID)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getCaptainProfileByID($captainProfileId)
    {
        return $this->createQueryBuilder('captainProfile')
            ->addSelect('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.state as vacationStatus', 'captainProfile.bounce', 'captainProfile.uuid', 'captainProfile.specialLink', 'captainProfile.isOnline', 'captainProfile.newMessageStatus', 'captainProfile.bankName', 'captainProfile.bankAccountNumber', 'captainProfile.stcPay')
            ->addSelect('acceptedOrderEntity.state')

            ->leftJoin(AcceptedOrderEntity::class, 'acceptedOrderEntity', Join::WITH, 'acceptedOrderEntity.captainID = captainProfile.captainID')

            ->andWhere('captainProfile.id=:captainProfileId')
            
            ->setParameter('captainProfileId', $captainProfileId)

            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getCaptainsInactive()
    {
        return $this->createQueryBuilder('captainProfile')
            ->addSelect('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.specialLink')

            ->andWhere("captainProfile.status = 'inactive' ")

            ->getQuery()
            ->getResult();
    }

    public function getCaptainsState($state)
    {
        return  $this->createQueryBuilder('captainProfile')
         
            ->select('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.bounce', 'captainProfile.specialLink')
            ->addSelect('acceptedOrderEntity.captainID', 'acceptedOrderEntity.state')
            
            ->leftJoin(AcceptedOrderEntity::class, 'acceptedOrderEntity', Join::WITH, 'acceptedOrderEntity.captainID = captainProfile.captainID')
            ->andWhere("acceptedOrderEntity.state = 'in store' or acceptedOrderEntity.state = 'picked' or acceptedOrderEntity.state = 'ongoing' or acceptedOrderEntity.state = 'cash'") 
            // ->andWhere('acceptedOrderEntity.state =:state')
            ->addGroupBy('captainProfile.id')
            ->addGroupBy('captainProfile.captainID')
            ->addGroupBy('captainProfile.name')
            ->addGroupBy('captainProfile.image')
            ->addGroupBy('captainProfile.location')
            ->addGroupBy('captainProfile.age')
            ->addGroupBy('captainProfile.car')
            ->addGroupBy('captainProfile.drivingLicence')
            ->addGroupBy('captainProfile.salary')
            ->addGroupBy('captainProfile.status')
            ->addGroupBy('captainProfile.bounce')
            ->addGroupBy('captainProfile.specialLink')
            ->addGroupBy('acceptedOrderEntity.state')
            // ->setParameter('state', $state)
            ->getQuery()
            ->getResult();
    }

    public function captainIsActive($captainID)
    {
        return $this->createQueryBuilder('captainProfile')
            ->select('captainProfile.status')

            ->andWhere('captainProfile.captainID = :captainID')

            ->setParameter('captainID', $captainID)

            ->getQuery()
            ->getResult();
    }

    public function countpendingCaptains()
    {
        return $this->createQueryBuilder('captainProfile')
            ->select('count (captainProfile.id) as countPendingCaptains')

            ->andWhere("captainProfile.status = 'inactive'")

            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function countOngoingCaptains()
    {
        return $this->createQueryBuilder('captainProfile')
            ->select('count (acceptedOrderEntity.captainID) as countOngoingCaptains')

            ->join(AcceptedOrderEntity::class, 'acceptedOrderEntity')

            ->andWhere("acceptedOrderEntity.state = 'ongoing'")

            ->getQuery()
            ->getOneOrNullResult();
    }
   
    public function countDayOfCaptains()
    {
        return $this->createQueryBuilder('captainProfile')
            ->select('count (captainProfile.id) as countDayOfCaptains')

            ->andWhere("captainProfile.state = 'vacation'")

            ->getQuery()
            ->getOneOrNullResult();
    }
   
    public function getCaptainsInVacation()
    {
        return $this->createQueryBuilder('captainProfile')
            ->select('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.state', 'captainProfile.specialLink')

            ->andWhere("captainProfile.state = 'vacation'")

            ->getQuery()
            ->getResult();
    }

    public function getCaptainAsArray($id)
    {
        return $this->createQueryBuilder('captainProfile')

            ->select('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.bounce', 'captainProfile.specialLink', 'captainProfile.bankName', 'captainProfile.bankAccountNumber', 'captainProfile.stcPay')

            ->andWhere('captainProfile.id =:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function getCaptainAsArrayByCaptainId($captainId)
    {
        return $this->createQueryBuilder('captainProfile')

            ->select('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.bounce', 'captainProfile.specialLink', 'captainProfile.bankName', 'captainProfile.bankAccountNumber', 'captainProfile.stcPay')

            ->andWhere('captainProfile.captainID =:captainID')
            ->setParameter('captainID', $captainId)
            ->getQuery()
            ->getResult();
    }

    public function getCaptains()
    {
        return $this->createQueryBuilder('captainProfile')

            ->select('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name as captainName', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.bounce', 'captainProfile.specialLink','captainProfile.newMessageStatus')

            ->addSelect('acceptedOrderEntity.captainID')

            ->addSelect('orderEntity.id as orderID', 'orderEntity.date', 'orderEntity.source', 'orderEntity.fromBranch', 'orderEntity.payment', 'orderEntity.destination','branchesEntity.location','branchesEntity.brancheName','branchesEntity.city as branchCity','orderEntity.ownerID')

            ->addSelect('userProfileEntity.id', 'userProfileEntity.userID', 'userProfileEntity.userName', 'userProfileEntity.image', 'userProfileEntity.story', 'userProfileEntity.free', 'userProfileEntity.branch as branchcount')
       
            
            ->leftJoin(AcceptedOrderEntity::class, 'acceptedOrderEntity', Join::WITH, 'captainProfile.captainID = acceptedOrderEntity.captainID')

            ->leftJoin(OrderEntity::class, 'orderEntity', Join::WITH, 'acceptedOrderEntity.orderID  = orderEntity.id')

            ->leftJoin(StoreOwnerBranchEntity::class, 'branchesEntity', Join::WITH, 'orderEntity.fromBranch = branchesEntity.id')

            ->leftJoin(StoreOwnerProfileEntity::class, 'userProfileEntity', Join::WITH, 'orderEntity.ownerID = userProfileEntity.userID')

            ->getQuery()
            ->getResult();
    }

    public function getAllCaptains()
    {
        return $this->createQueryBuilder('captainProfile')
            ->select('captainProfile.id', 'captainProfile.captainID', 'captainProfile.name', 'captainProfile.image', 'captainProfile.location', 'captainProfile.age', 'captainProfile.car', 'captainProfile.drivingLicence', 'captainProfile.salary', 'captainProfile.status', 'captainProfile.state', 'captainProfile.uuid', 'captainProfile.bounce', 'captainProfile.specialLink', 'captainProfile.isOnline', 'captainProfile.newMessageStatus', 'captainProfile.bankName', 'captainProfile.bankAccountNumber', 'captainProfile.stcPay')

            ->getQuery()
            ->getResult();
    }
    
    public function getcaptainByUuid($uuid)
    {
        return $this->createQueryBuilder('captainProfile')
            ->andWhere('captainProfile.uuid = :uuid')
            ->setParameter('uuid',$uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTop5Captains()
    {
        return $this->createQueryBuilder('captainProfileEntity')
           
            ->select( 'captainProfileEntity.name', 'captainProfileEntity.car', 'captainProfileEntity.age', 'captainProfileEntity.salary', 'captainProfileEntity.bounce', 'captainProfileEntity.image', 'captainProfileEntity.specialLink')

            ->addSelect('OrderEntity.captainID', 'count(OrderEntity.captainID) countOrdersDeliverd')

            ->leftJoin(OrderEntity::class, 'OrderEntity', Join::WITH, 'OrderEntity.captainID = captainProfileEntity.captainID')

            // ->andWhere("OrderEntity.state ='deliverd'")
           
            ->addGroupBy('OrderEntity.captainID')
            
            ->having('count(OrderEntity.captainID) > 0')
            ->setMaxResults(5)
            ->addOrderBy('countOrdersDeliverd','DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTopCaptainsInLastMonthDate($fromDate, $toDate)
    {
        return $this->createQueryBuilder('captainProfileEntity')

            ->select('captainProfileEntity.name', 'captainProfileEntity.car', 'captainProfileEntity.age', 'captainProfileEntity.salary', 'captainProfileEntity.bounce', 'captainProfileEntity.image', 'captainProfileEntity.specialLink', 'captainProfileEntity.drivingLicence')
            
            ->addSelect('OrderEntity.captainID', 'count(OrderEntity.captainID) countOrdersInMonth')
           
            ->leftJoin(OrderEntity::class, 'OrderEntity', Join::WITH, 'OrderEntity.captainID = captainProfileEntity.captainID')

             ->where('OrderEntity.date >= :fromDate')
             ->andWhere('OrderEntity.date < :toDate')
            // ->andWhere("OrderEntity.state ='deliverd'")
        
            ->addGroupBy('OrderEntity.captainID')
            
            ->having('count(OrderEntity.captainID) > 0')
            ->setMaxResults(15)
            ->addOrderBy('countOrdersInMonth','DESC')
         
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate)
            ->getQuery()
            ->getResult();
    }

}
