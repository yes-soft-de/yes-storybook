<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\StoreOwnerPaymentEntity;
use App\Manager\StoreOwnerPaymentManager;
use App\Request\StoreOwnerPaymentCreateRequest;
use App\Response\StoreOwnerCreateResponse;
use App\Service\StoreOwnerSubscriptionService;
use App\Service\DateFactoryService;
class StoreOwnerPaymentService
{
    private $autoMapping;
    private $storeOwnerPaymentManager;
    private $storeOwnersubscriptionService;
    private $dateFactoryService;

    public function __construct(AutoMapping $autoMapping, StoreOwnerPaymentManager $storeOwnerPaymentManager, StoreOwnerSubscriptionService $storeOwnersubscriptionService, DateFactoryService $dateFactoryService)
    {
        $this->autoMapping = $autoMapping;
        $this->storeOwnerPaymentManager = $storeOwnerPaymentManager;
        $this->storeOwnersubscriptionService = $storeOwnersubscriptionService;
        $this->dateFactoryService = $dateFactoryService;
    }

    public function createStoreOwnerPayment(StoreOwnerPaymentCreateRequest $request)
    {
        $item = $this->storeOwnerPaymentManager->createStoreOwnerPayment($request);

        return $this->autoMapping->map(StoreOwnerPaymentEntity::class, StoreOwnerCreateResponse::class, $item);
    }

    public function getpaymentsForOwner($ownerId, $admin='null')
    {
       $response = [];

       $totalAmountOfSubscriptions= $this->storeOwnersubscriptionService->totalAmountOfSubscriptions($ownerId);
       
        $items = $this->storeOwnerPaymentManager->getpaymentsForOwner($ownerId);
      
        $sumPayments = $this->storeOwnerPaymentManager->getSumAmount($ownerId);
       
        $NewAmount = $this->storeOwnerPaymentManager->getNewAmount($ownerId);
        $nextPay = null;
        if ($NewAmount){
            $nextPay = $this->dateFactoryService->returnNextPaymentDate($NewAmount[0]['date']);
        }
        $sumPayments = $sumPayments[0]['sumPayments'];
       
        

        $total = $sumPayments - $totalAmountOfSubscriptions;
        
        if ($admin == "admin"){$total = $totalAmountOfSubscriptions - $sumPayments;}
        
        foreach ($items as $item) {  
            $response[]=  $this->autoMapping->map('array', StoreOwnerCreateResponse::class, $item);  
        }
        
      $arr['payments'] = $response;
      $arr['nextPay'] = $nextPay;
      $arr['sumPayments'] = $sumPayments;
      $arr['totalAmountOfSubscriptions'] = $totalAmountOfSubscriptions;
      $arr['currentTotal'] = $total;
      return $arr;
    }
}
