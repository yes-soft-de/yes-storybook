<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\SupportEntity;
use App\Manager\SupportManager;
use App\Request\SupportCreateRequest;
use App\Response\SupportResponse;
use App\Service\RoomIdHelperService;

class SupportService
{
    private $autoMapping;
    private $supportManager;
    private $roomIdHelperService;

    public function __construct(AutoMapping $autoMapping, SupportManager $supportManager, RoomIdHelperService $roomIdHelperService)
    {
        $this->autoMapping = $autoMapping;
        $this->supportManager = $supportManager;
        $this->roomIdHelperService = $roomIdHelperService;
    }

    public function createSupport(SupportCreateRequest $request)
    {
        $uuid = $this->roomIdHelperService->roomIdGenerate();
        
        $reprot = $this->supportManager->createSupport($request, $uuid);

        return $this->autoMapping->map(SupportEntity::class, SupportResponse::class, $reprot);
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

    public function updateReportNewMessageStatus($id)
    {
        return $this->supportManager->updateReportNewMessageStatus($id);
   
       
     }
}