<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\StoreOwnerPaymentEntity;
use App\Repository\storeOwnerPaymentEntityRepository;
use App\Request\StoreOwnerPaymentCreateRequest;
use Doctrine\ORM\EntityManagerInterface;

class StoreOwnerPaymentManager
{
    private $autoMapping;
    private $entityManager;
    private $repository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, storeOwnerPaymentEntityRepository $repository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function create(StoreOwnerPaymentCreateRequest $request)
    {
        $entity = $this->autoMapping->map(StoreOwnerPaymentCreateRequest::class, StoreOwnerPaymentEntity::class, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function getpaymentsForOwner($ownerId)
    {
        return $this->repository->getpaymentsForOwner($ownerId);
    }

    public function getSumAmount($ownerId)
    {
        return $this->repository->getSumAmount($ownerId);
    }

    public function getNewAmount($ownerId)
    {
        return $this->repository->getNewAmount($ownerId);
    }
}
