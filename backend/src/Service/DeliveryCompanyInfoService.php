<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\DeliveryCompanyInfoEntity;
use App\Manager\DeliveryCompanyInfoManager;
use App\Request\DeliveryCompanyInfoRequest;
use App\Response\DeliveryCompanyInfoResponse;

class DeliveryCompanyInfoService
{
    private $autoMapping;
    private $deliveryCompanyInfoManager;

    public function __construct(AutoMapping $autoMapping, DeliveryCompanyInfoManager $deliveryCompanyInfoManager)
    {
        $this->autoMapping = $autoMapping;
        $this->deliveryCompanyInfoManager = $deliveryCompanyInfoManager;
    }

    public function create(DeliveryCompanyInfoRequest $request)
    {
        $item = $this->deliveryCompanyInfoManager->create($request);
        if ($item instanceof DeliveryCompanyInfoEntity) {
        return $this->autoMapping->map(DeliveryCompanyInfoEntity::class, DeliveryCompanyInfoResponse::class, $item);
        }
        if ($item == true) {
          
            return $this->getcompanyinfoAll();
        }
    }

    public function update($request)
    {
        $result = $this->deliveryCompanyInfoManager->update($request);

        return $this->autoMapping->map(DeliveryCompanyInfoEntity::class, DeliveryCompanyInfoResponse::class, $result);
    }

    public function  getcompanyinfoById($id)
    {
        $result = $this->deliveryCompanyInfoManager->getcompanyinfoById($id);

        return $this->autoMapping->map(DeliveryCompanyInfoEntity::class, DeliveryCompanyInfoResponse::class, $result);
  
    }

    public function  getcompanyinfoAll()
    {
        $respons=[];
        $results = $this->deliveryCompanyInfoManager->getcompanyinfoAll();
       
        foreach ($results as  $result) {
           $respons[]= $this->autoMapping->map('array', DeliveryCompanyInfoResponse::class, $result);
        }
        return $respons;
       
    }

     public function  getAllCompanyInfoForStoreOwner($userId)
    {
        $respons=[];
        $results = $this->deliveryCompanyInfoManager->getAllCompanyInfoForStoreOwner($userId);
       
        foreach ($results as  $result) {
           $respons[]= $this->autoMapping->map('array', DeliveryCompanyInfoResponse::class, $result);
        }
        return $respons;
       
    }

    public function  getAllCompanyInfoForCaptain($userId)
    {
        $respons=[];
        $results = $this->deliveryCompanyInfoManager->getAllCompanyInfoForCaptain($userId);
       
        foreach ($results as  $result) {
           $respons[]= $this->autoMapping->map('array', DeliveryCompanyInfoResponse::class, $result);
        }
        return $respons;
       
    }

}
