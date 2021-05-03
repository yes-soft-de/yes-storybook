<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\StoreOwnerBranchEntity;
use App\Manager\StoreOwnerBranchManager;
use App\Request\BranchesCreateRequest;
use App\Response\BranchesResponse;

class StoreOwnerBranchService
{
    private $autoMapping;
    private $storeOwnerBranchManager;

    public function __construct(AutoMapping $autoMapping, StoreOwnerBranchManager $storeOwnerBranchManager)
    {
        $this->autoMapping = $autoMapping;
        $this->storeOwnerBranchManager = $storeOwnerBranchManager;
    }

    public function createBranches(BranchesCreateRequest $request)
    {
        $branche = $this->storeOwnerBranchManager->createBranches($request);

        return $this->autoMapping->map(StoreOwnerBranchEntity::class, BranchesResponse::class, $branche);
    }

    public function updateBranche($request)
    {
        $result = $this->storeOwnerBranchManager->updateBranche($request);

        return $this->autoMapping->map(StoreOwnerBranchEntity::class, BranchesResponse::class, $result);
    }

    public function getBranchesByUserId($userId):array
    {
        $response = [];
        $items = $this->storeOwnerBranchManager->getBranchesByUserId($userId);
        foreach ($items as $item) {
        $response[] =  $this->autoMapping->map('array', BranchesResponse::class, $item);
        }
        return $response;
    }

    public function branchesByUserId($userId)
    {
        return $this->storeOwnerBranchManager->branchesByUserId($userId);
    }

    public function getBrancheById($Id)
    {
        return $this->storeOwnerBranchManager->getBrancheById($Id);
    }
    
    public function updateBranchAvailability($request)
    {
        $result = $this->storeOwnerBranchManager->updateBranchAvailability($request);

        return $this->autoMapping->map(StoreOwnerBranchEntity::class, BranchesResponse::class, $result);
    }
}
