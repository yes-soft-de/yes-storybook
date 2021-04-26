<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\LogEntity;
use App\Manager\LogManager;
use App\Response\LogResponse;

class LogService
{
    private $autoMapping;
    private $logManager;

    public function __construct(AutoMapping $autoMapping, LogManager $logManager)
    {
        $this->autoMapping = $autoMapping;
        $this->logManager = $logManager;
    }

    public function create($orderID, $state)
    {
        $record['orderID'] = $orderID;
        $record['state'] = $state;
        $result = $this->logManager->create($record);

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
        $records = $this->getLogsByOrderId($orderId);
      
        foreach ($records as $rec) {
         
            $firstDate = $this->getFirstDate($rec['orderID']); 
            $lastDate = $this->getLastDate($rec['orderID']);
           
            if($firstDate[0]['date'] && $lastDate[0]['date']) {
                $state['completionTime'] = $this->subtractTowDates($firstDate[0]['date'], $lastDate[0]['date']);
            }
            
            $state['finalOrder'] = $lastDate[0]['state'] ;
            $orderStatus = $this->autoMapping->map('array', RecordResponse::class, $state);
            $record[] = $this->autoMapping->map('array', RecordResponse::class, $rec);

    } 
        if($firstDate && $lastDate) {
            $response['orderStatus'] = $orderStatus ;
            $response['record'] = $record ;
            }
        return  $response;
    }

    public  function subtractTowDates($firstDate, $lastDate) {
        
        $difference = $firstDate->diff($lastDate);
        
        return $this->format_interval($difference);
    }

    public function getFirstDate($orderId)
    {
        return $this->recordManager->getFirstDate($orderId);
    }

    public function getLastDate($orderId)
    {
        return $this->recordManager->getLastDate($orderId);
    }

    function format_interval($interval) {
        $result = "";
        if ($interval->y) { $result .= $interval->format("%y years "); }
        if ($interval->m) { $result .= $interval->format("%m months "); }
        if ($interval->d) { $result .= $interval->format("%d days "); }
        if ($interval->h) { $result .= $interval->format("%h hours "); }
        if ($interval->i) { $result .= $interval->format("%i minutes "); }
        if ($interval->s) { $result .= $interval->format("%s seconds "); }
    
        return $result;
    } 
}
