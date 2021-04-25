<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\CompanyInfoEntity;
use App\Repository\DeliveryCompanyInfoEntityRepository;
use App\Request\DeliveryCompanyInfoRequest;
use App\Request\companyInfoUpdateRequest;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryCompanyInfoManager
{
    private $autoMapping;
    private $entityManager;
    private $deliveryCompanyInfoEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, DeliveryCompanyInfoEntityRepository $deliveryCompanyInfoEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->deliveryCompanyInfoEntityRepository = $deliveryCompanyInfoEntityRepository;
    }

    public function create(DeliveryCompanyInfoRequest $request)
    {
       $isfound = $this->getcompanyinfoAll();
        if ($isfound == null) {
        $entity = $this->autoMapping->map(DeliveryCompanyInfoRequest::class, CompanyInfoEntity::class, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
        }
        else {
            return true;
        }
    }

    public function update(companyInfoUpdateRequest $request)
    {
        $entity = $this->deliveryCompanyInfoEntityRepository->find($request->getId());

        if (!$entity) {
            return null;
        }
        $entity = $this->autoMapping->mapToObject(companyInfoUpdateRequest::class, CompanyInfoEntity::class, $request, $entity);

        $this->entityManager->flush();

        return $entity;
    } 

    public function getcompanyinfoById($id)
    {
        return $this->deliveryCompanyInfoEntityRepository->getcompanyinfoById($id);
    }

    public function getcompanyinfoAll()
    {
       return $this->deliveryCompanyInfoEntityRepository->getcompanyinfoAll();
    }

    public function getcompanyinfoAllOwner($userId)
    {
       return $this->deliveryCompanyInfoEntityRepository->getcompanyinfoAllOwner($userId);
    }

    public function getcompanyinfoAllCaptain($userId)
    {
       return $this->deliveryCompanyInfoEntityRepository->getcompanyinfoAllCaptain($userId);
    }
}
