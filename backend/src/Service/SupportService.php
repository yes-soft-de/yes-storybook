<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\ReportEntity;
use App\Manager\SupportManager;
use App\Request\SupportCreateRequest;
use App\Response\SupportResponse;


class SupportService
{
    private $autoMapping;
    private $supportManager;
    private $logService;

    public function __construct(AutoMapping $autoMapping, SupportManager $supportManager, LogService $logService)
    {
        $this->autoMapping = $autoMapping;
        $this->supportManager = $supportManager;
        $this->logService = $logService;
    }

    public function create(SupportCreateRequest $request)
    {
        $uuid =$this->logService->uuid();
        
        $reprot = $this->supportManager->create($request, $uuid);

        return $this->autoMapping->map(ReportEntity::class, SupportResponse::class, $reprot);
    }

    public function getReports()
    {
        $response = [];
        $items = $this->supportManager->getReports();
        foreach ($items as $item) {
        $response[] =  $this->autoMapping->map('array', SupportResponse::class, $item);
        }
        return $response;
    }

    public function getReport($id)
    {
       
        $item = $this->supportManager->getReport($id);
    
        return  $this->autoMapping->map('array', SupportResponse::class, $item);
    }

    public function update($request, $NewMessageStatus)
    {
        $item = $this->supportManager->getReportByUuid($request->getRoomID());
   
        return $this->supportManager->update($item, $NewMessageStatus);
     }

    public function reportUpdateNewMeessageStatus($id)
    {
        return $this->supportManager->reportUpdateNewMeessageStatus($id);
   
       
     }
}