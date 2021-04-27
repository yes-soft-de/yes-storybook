<?php

namespace App\Service;

use App\AutoMapping;
use App\Manager\AcceptedOrderManager;
use App\Response\AcceptedOrdersResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Service\LogService;
use App\Service\DateFactoryService;

class AcceptedOrderFilterService
{
    private $autoMapping;
    private $acceptedOrderManager;
    private $logService;
    private $params;
    private $dateFactoryService;

    public function __construct(AutoMapping $autoMapping, AcceptedOrderManager $acceptedOrderManager, ParameterBagInterface $params, LogService $logService, DateFactoryService $dateFactoryService)
    {
        $this->autoMapping = $autoMapping;
        $this->acceptedOrderManager = $acceptedOrderManager;
        $this->logService = $logService;
        $this->params = $params->get('upload_base_url') . '/';
        $this->dateFactoryService = $dateFactoryService;
    }

    public function countOrdersDeliverd($userID)
    {
        return $this->acceptedOrderManager->countOrdersDeliverd($userID);
    }

    public function getAcceptedOrderByOrderId($orderId)
    {
        return $this->acceptedOrderManager->getAcceptedOrderByOrderId($orderId);
    }

    public function getAcceptedOrderByCaptainId($captainId)
    {
        $response = [];
        $orders = $this->acceptedOrderManager->getAcceptedOrderByCaptainId($captainId);
   
        foreach ($orders as $order){
            $order['record'] = $this->logService->getLogByOrderId($order['orderID']);
            $response[] = $this->autoMapping->map('array', AcceptedOrdersResponse::class, $order);
        }
    
    return $response;
    }

    public function countAcceptedOrder($captainId)
    {
        return $this->acceptedOrderManager->countAcceptedOrder($captainId);
    }

    public function getTop5Captains()
     {
        return $this->acceptedOrderManager->getTop5Captains();
     }

    public function countOrdersInMonthForCaptin($fromDate, $toDate, $captainId)
     {
         return $this->acceptedOrderManager->countOrdersInMonthForCaptin($fromDate, $toDate, $captainId);
     }

    public function getAcceptedOrderByCaptainIdInMonth($fromDate, $toDate, $captainId)
     {
         return $this->acceptedOrderManager->getAcceptedOrderByCaptainIdInMonth($fromDate, $toDate, $captainId);
     }

    public function getTopCaptainsInLastMonthDate():array
    {
       $response = [];
       $date = $this->dateFactoryService->returnLastMonthDate();
       $topCaptains = $this->acceptedOrderManager->getTopCaptainsInLastMonthDate($date[0],$date[1]);
     
        foreach ($topCaptains as $topCaptain) {
            $topCaptain['imageURL'] = $topCaptain['image'];
            $topCaptain['image'] = $this->params.$topCaptain['image'];
            $topCaptain['drivingLicenceURL'] = $topCaptain['drivingLicence'];
            $topCaptain['drivingLicence'] = $this->params.$topCaptain['drivingLicence'];
            $topCaptain['baseURL'] = $this->params;
            $response[] = $this->autoMapping->map('array', AcceptedOrdersResponse::class, $topCaptain);
        }
    
       return $response;
   }

    public function specialLinkCheck($bool)
    {
        if (!$bool)
        {
            return $this->params;
        }
    }

    public function countOrdersInDay($captainID, $fromDate, $toDate)
     {
         return $this->acceptedOrderManager->countOrdersInDay($captainID, $fromDate, $toDate);
     }

    public function getOwnerIdAndUuid($orderId)
     {
         return $this->acceptedOrderManager->getOwnerIdAndUuid($orderId);
     }
}
