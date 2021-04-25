<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\PackageEntity;
use App\Manager\DeliveryCompanyPackageManager;
use App\Request\DeliveryCompanyPackageCreateRequest;
use App\Response\DeliveryCompanyPackageResponse;

class DeliveryCompanyPackageService
{
    private $autoMapping;
    private $deliveryCompanyPackageManager;

    public function __construct(AutoMapping $autoMapping, DeliveryCompanyPackageManager $deliveryCompanyPackageManager)
    {
        $this->autoMapping = $autoMapping;
        $this->deliveryCompanyPackageManager = $deliveryCompanyPackageManager;
    }

    public function create(DeliveryCompanyPackageCreateRequest $request)
    {
        $result = $this->deliveryCompanyPackageManager->create($request);

        return $this->autoMapping->map(PackageEntity::class, DeliveryCompanyPackageResponse::class, $result);
    }

    public function getPackages()
    {
        $respons = [];
        $items = $this->deliveryCompanyPackageManager->getPackages();

        foreach ($items as $item) {
            $respons[] = $this->autoMapping->map('array', DeliveryCompanyPackageResponse::class, $item);
        }
        return $respons;
    }

    public function getAllpackages()
    {
        return $this->deliveryCompanyPackageManager->getAllpackages();
    }

    public function getpackagesById($id)
    {
        return $this->deliveryCompanyPackageManager->getpackagesById($id);
    }

    public function update($request)
    {
        $result = $this->deliveryCompanyPackageManager->update($request);

        return $this->autoMapping->map(PackageEntity::class, DeliveryCompanyPackageResponse::class, $result);
    }
}
