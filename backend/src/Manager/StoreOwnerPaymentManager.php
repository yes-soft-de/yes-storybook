<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\StoreOwnerPaymentEntity;
use App\Repository\StoreOwnerPaymentEntityRepository;
use App\Request\StoreOwnerPaymentCreateRequest;
use Doctrine\ORM\EntityManagerInterface;

class StoreOwnerPaymentManager
{
    private $autoMapping;
    private $entityManager;
    private $storeOwnerPaymentEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, StoreOwnerPaymentEntityRepository $storeOwnerPaymentEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->storeOwnerPaymentEntityRepository = $storeOwnerPaymentEntityRepository;
    }

    public function createStoreOwnerPayment(StoreOwnerPaymentCreateRequest $request)
    {
        $entity = $this->autoMapping->map(StoreOwnerPaymentCreateRequest::class, StoreOwnerPaymentEntity::class, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function getpaymentsForOwner($ownerId)
    {
        return $this->storeOwnerPaymentEntityRepository->getpaymentsForOwner($ownerId);
    }

    public function getSumAmount($ownerId)
    {
        return $this->storeOwnerPaymentEntityRepository->getSumAmount($ownerId);
    }

    public function getNewAmount($ownerId)
    {
        return $this->storeOwnerPaymentEntityRepository->getNewAmount($ownerId);
    }
}
