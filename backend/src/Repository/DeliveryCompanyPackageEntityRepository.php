<?php

namespace App\Repository;

use App\Entity\DeliveryCompanyPackageEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeliveryCompanyPackageEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryCompanyPackageEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryCompanyPackageEntity[]    findAll()
 * @method DeliveryCompanyPackageEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryCompanyPackageEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryCompanyPackageEntity::class);
    }

    public function getPackages()
    {
        return $this->createQueryBuilder('package')
            ->select('package.id, package.name, package.cost, package.note, package.carCount, package.orderCount, package.status, package.city, package.branch')

            ->andWhere("package.status = 'active'")

            ->getQuery()
            ->getResult();
    }

    // get Packages User Compatible
    // public function getPackages($user)
    // {
    //     return $this->createQueryBuilder('package')
    //         ->select('package.id, package.name, package.cost, package.note, package.carCount, package.orderCount, package.status, package.city, package.branch')
    //         ->join(UserProfileEntity::class, 'userProfileEntity', Join::WITH, 'userProfileEntity.userID = :user')
    //         ->where("package.status = 'active'")
    //         // ->andWhere('userProfileEntity.branch = package.branch')
    //         ->andWhere('userProfileEntity.city = package.city')
    //         ->setParameter('user', $user)
    //         ->groupBy('package.id')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function getAllpackages()
    {
        return $this->createQueryBuilder('package')
            ->select('package.id, package.name, package.cost, package.note, package.carCount, package.city, package.orderCount, package.status')

            ->getQuery()
            ->getResult();
    }
    public function getpackagesById($id)
    {
        return $this->createQueryBuilder('package')
            ->select('package.id, package.name, package.cost, package.note, package.carCount, package.city, package.orderCount, package.status')
            ->andWhere('package.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
