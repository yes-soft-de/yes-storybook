<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\StoreOwnerBranchEntity;
use App\Repository\StoreOwnerBranchEntityRepository;
use App\Request\BranchesCreateRequest;
use App\Request\BranchesUpdateRequest;
use App\Request\BranchesDeleteRequest;
use Doctrine\ORM\EntityManagerInterface;

class StoreOwnerBranchManager
{
    private $autoMapping;
    private $entityManager;
    private $storeOwnerBranchEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, StoreOwnerBranchEntityRepository $storeOwnerBranchEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->storeOwnerBranchEntityRepository = $storeOwnerBranchEntityRepository;
    }

    public function createBranches(BranchesCreateRequest $request)
    {
        $entity = $this->autoMapping->map(BranchesCreateRequest::class, StoreOwnerBranchEntity::class, $request);
        $entity->setIsActive(1);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function updateBranche(BranchesUpdateRequest $request)
    {
        $entity = $this->storeOwnerBranchEntityRepository->find($request->getId());

        if (!$entity) {
            return null;
        }
        
        $entity = $this->autoMapping->mapToObject(BranchesUpdateRequest::class, StoreOwnerBranchEntity::class, $request, $entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function getBranchesByUserId($userId)
    {
        return $this->storeOwnerBranchEntityRepository->getBranchesByUserId($userId);
    }

    public function branchesByUserId($userId)
    {
        return $this->storeOwnerBranchEntityRepository->branchesByUserId($userId);
    }

    public function getBrancheById($Id)
    {
        return $this->storeOwnerBranchEntityRepository->find($Id);
    }

    public function updateBranchAvailability(BranchesDeleteRequest $request)
    {
        $entity = $this->storeOwnerBranchEntityRepository->find($request->getId());

        if (!$entity) {
            return null;
        }
        $entity = $this->autoMapping->mapToObject(BranchesDeleteRequest::class, StoreOwnerBranchEntity::class, $request, $entity);

        $this->entityManager->flush();

        return $entity;
    }
}
