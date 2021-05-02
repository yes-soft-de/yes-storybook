<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\LogEntity;
use App\Manager\LogManager;
use App\Response\LogResponse;
use App\Service\DateFactoryService;
class LogService
{
    private $autoMapping;
    private $logManager;
    private $dateFactoryService;

    public function __construct(AutoMapping $autoMapping, LogManager $logManager, DateFactoryService $dateFactoryService)
    {
        $this->autoMapping = $autoMapping;
        $this->logManager = $logManager;
        $this->dateFactoryService = $dateFactoryService;
    }

    public function createLog($orderID, $state, $userID)
    {
        $item['orderID'] = $orderID;
        $item['state'] = $state;
        $item['userID'] = $userID;
        
        $result = $this->logManager->createLog($item);

        return $this->autoMapping->map(LogEntity::class, LogResponse::class, $result);
    }
    
    public function getLogByOrderId($orderId)
    {
        return $this->logManager->getLogByOrderId($orderId);
    }

    public function getLogsByOrderId($orderId)
    {
        return $this->logManager->getLogsByOrderId($orderId);
    }

    public function getLogsWithcompletionTime($orderId)
    {
        $response=[];
        $items = $this->getLogsByOrderId($orderId);
     
        foreach ($items as $item) {
         
            $firstDate = $this->getFirstDate($item['orderID']); 
            $lastDate = $this->getLastDate($item['orderID']);
           
            if($firstDate[0]['date'] && $lastDate[0]['date']) {
                $state['completionTime'] = $this->dateFactoryService->subtractTwoDates($firstDate[0]['date'], $lastDate[0]['date']);
            }
            $record[] = $this->autoMapping->map('array', LogResponse::class, $item);
        } 
        $state['currentStage'] = $lastDate[0]['state'] ;
        $orderStatus[] = $this->autoMapping->map('array', LogResponse::class, $state);
        if($firstDate && $lastDate) {
            $response['orderStatus'] = $orderStatus ;
            $response['record'] = $record ;
            }
        return  $response;
    }

    public function getFirstDate($orderId)
    {
        return $this->logManager->getFirstDate($orderId);
    }

    public function getLastDate($orderId)
    {
        return $this->logManager->getLastDate($orderId);
    } 

    public function getLogsByStoreOwner($ownerID)
    {
        $response = [];
      
        $items = $this->logManager->getOrderIdByOwnerId($ownerID);
     
            foreach ($items as $item) {
                $item['record'] = $this->getLogsByOrderId($item['orderID']);
               
                $firstDate = $this->getFirstDate($item['orderID']); 
                $lastDate = $this->getLastDate($item['orderID']);
               
                $item['currentStage'] =  $lastDate;
                if($firstDate[0]['date'] && $lastDate[0]['date']) {
                    $item['completionTime'] = $this->dateFactoryService->subtractTwoDates($firstDate[0]['date'], $lastDate[0]['date']);
                }
                $response[] = $this->autoMapping->map('array', LogResponse::class, $item);
            }
            return $response;
    }

    public function getLogsByCaptain($captainID)
    {
         $response = [];
      
        $items = $this->logManager->getOrderIdByCaptainId($captainID);
   
            foreach ($items as $item) {
                $item['record'] = $this->getLogsByOrderId($item['orderID']);
               
                $firstDate = $this->getFirstDate($item['orderID']); 
                $lastDate = $this->getLastDate($item['orderID']);
               
                $item['currentStage'] =  $lastDate;
                if($firstDate[0]['date'] && $lastDate[0]['date']) {
                    $item['completionTime'] = $this->dateFactoryService->subtractTwoDates($firstDate[0]['date'], $lastDate[0]['date']);
                }
                $response[] = $this->autoMapping->map('array', LogResponse::class, $item);
            }
            return $response;
    }

}
