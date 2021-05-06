<?php

namespace App\Service;

use App\AutoMapping;
use App\Manager\OrderManager;
use App\Response\OrderResponse;
use App\Service\LogService;

class AcceptedOrderService
{
    private $autoMapping;
    private $orderManager;
    private $logService;

    public function __construct(AutoMapping $autoMapping, OrderManager $orderManager, LogService $logService)
    {
        $this->autoMapping = $autoMapping;
        $this->orderManager = $orderManager;
        $this->logService = $logService;
    }

    public function getAcceptedOrderByCaptainId($captainID):array
    {
        $response = [];
        $orders = $this->orderManager->getAcceptedOrderByCaptainId($captainID);
   
        foreach ($orders as $order){
            $order['record'] = $this->logService->getLogByOrderId($order['id']);
            $response[] = $this->autoMapping->map('array', OrderResponse::class, $order);
        }
    
        return $response;
    }

    public function countCaptainOrdersDelivered($captainId)
    {
        return $this->orderManager->countCaptainOrdersDelivered($captainId);
    }

    public function countOrdersInMonthForCaptain($fromDate, $toDate, $captainId)
    {
        return $this->orderManager->countOrdersInMonthForCaptain($fromDate, $toDate, $captainId);
    }

    public function getAcceptedOrderByCaptainIdInMonth($fromDate, $toDate, $captainId)
    {
        return $this->orderManager->getAcceptedOrderByCaptainIdInMonth($fromDate, $toDate, $captainId);
    }

    public function countCaptainOrdersInDay($captainID, $fromDate, $toDate)
    {
        return $this->orderManager->countCaptainOrdersInDay($captainID, $fromDate, $toDate);
    }
}
