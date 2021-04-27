<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\AcceptedOrderEntity;
use App\Manager\AcceptedOrderManager;
use App\Request\AcceptedOrderCreateRequest;
use App\Response\AcceptedOrderResponse;
use App\Service\LogService;
use App\Service\RoomIdHelperService;
use App\Service\DateFactoryService;
use App\Service\AcceptedOrderFilterService;

class AcceptedOrderService
{
    private $autoMapping;
    private $acceptedOrderManager;
    private $logService;
    private $roomIdHelperService;
    private $acceptedOrderFilterService;

    public function __construct(AutoMapping $autoMapping, AcceptedOrderManager $acceptedOrderManager, LogService $logService,  RoomIdHelperService $roomIdHelperService, DateFactoryService $dateFactoryService, AcceptedOrderFilterService $acceptedOrderFilterService)
    {
        $this->autoMapping = $autoMapping;
        $this->acceptedOrderManager = $acceptedOrderManager;
        $this->logService = $logService;
        $this->roomIdHelperService = $roomIdHelperService;
        $this->dateFactoryService = $dateFactoryService;
        $this->acceptedOrderFilterService = $acceptedOrderFilterService;
    }

    public function createAcceptedOrder(AcceptedOrderCreateRequest $request)
    {   
        $response ="This order was received by another captain";
        $acceptedOrder = $this->acceptedOrderFilterService->getAcceptedOrderByOrderId($request->getOrderID());
        if (!$acceptedOrder) {
            $item = $this->acceptedOrderManager->createAcceptedOrder($request);
            if ($item) {
               $this->logService->createLog($item->getOrderID(), $item->getState());
               $data = $this->acceptedOrderFilterService->getOwnerIdAndUuid($item->getOrderID());
               $this->roomIdHelperService->create($data);
            }
            $response = $this->autoMapping->map(AcceptedOrderEntity::class, AcceptedOrderResponse::class, $item);
        }
       
        return $response;
    }

    public function updateAcceptedOrderStateByCaptain($orderId, $state)
    {
        $this->acceptedOrderManager->updateAcceptedOrderStateByCaptain($orderId, $state);
        $this->logService->createLog($orderId, $state);
    }

    public function countAcceptedOrder($captainId)
    {
        return $this->acceptedOrderManager->countAcceptedOrder($captainId);
    }    
}
