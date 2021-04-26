<?php

namespace App\Service;
use DateTime;

class DateFactoryService
{
    public function returnLastMonthDate():array
    {
        $dateNow =new DateTime("now");
        $year = $dateNow->format("Y");
        $month = $dateNow->format("m");
        $fromDate =new \DateTime($year . '-' . $month . '-01'); 
        $toDate = new \DateTime($fromDate->format('Y-m-d') . ' -1 month');
     //    if you want get  this month must change (-1 month) to (+1 month) in back line
     //    return [$fromDate,  $toDate];
 
     //    if you want get  last month must change (+1 month) to (-1 month) in back line
        return [$toDate,  $fromDate];
     }

}