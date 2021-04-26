<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\DeliveryCompanyPackageEntity;
use App\Repository\DeliveryCompanyPackageEntityRepository;
use App\Request\DeliveryCompanyPackageCreateRequest;
use App\Request\DeliveryCompanyPackageUpdateStateRequest;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryCompanyPackageManager
{
    private $autoMapping;
    private $entityManager;
    private $deliveryCompanyPackageRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, DeliveryCompanyPackageEntityRepository $deliveryCompanyPackageRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->deliveryCompanyPackageRepository = $deliveryCompanyPackageRepository;
    }

    public function create(DeliveryCompanyPackageCreateRequest $request)
    {
        $packageEntity = $this->autoMapping->map(DeliveryCompanyPackageCreateRequest::class, DeliveryCompanyPackageEntity::class, $request);

        $this->entityManager->persist($packageEntity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $packageEntity;
    }

    public function getPackages()
    {
        return $this->deliveryCompanyPackageRepository->getPackages();
    }

    public function getAllpackages()
    {
        return $this->deliveryCompanyPackageRepository->getAllpackages();
    }
    public function getpackagesById($id)
    {
        return $this->deliveryCompanyPackageRepository->getpackagesById($id);
    }

    public function update(DeliveryCompanyPackageUpdateStateRequest $request)
    {
        $entity = $this->deliveryCompanyPackageRepository->find($request->getId());

        if (!$entity) {
            return null;
        }
        $entity = $this->autoMapping->mapToObject(DeliveryCompanyPackageUpdateStateRequest::class, DeliveryCompanyPackageEntity::class, $request, $entity);

        $this->entityManager->flush();

        return $entity;
    }
}
