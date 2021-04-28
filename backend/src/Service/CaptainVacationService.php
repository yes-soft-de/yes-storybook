<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\CaptainVacationEntity;
use App\Manager\CaptainVacationManager;
use App\Request\CaptainVacationCreateRequest;
use App\Response\CaptainVacationResponse;
use App\Service\CaptainService;

class CaptainVacationService
{
    private $autoMapping;
    private $captainVacationManager;
    private $captainService;

    public function __construct(AutoMapping $autoMapping, CaptainVacationManager $captainVacationManager, CaptainService $captainService)
    {
        $this->autoMapping = $autoMapping;
        $this->captainService = $captainService;
        $this->captainVacationManager = $captainVacationManager;
    }

    public function createCaptainVacation(CaptainVacationCreateRequest $request)
    {
        $result = $this->captainVacationManager->createCaptainVacation($request);
        if ($result) {
           $this->captainService->updateCaptainStateByAdmin($request); 
        }
        $respnose = $this->autoMapping->map(CaptainVacationEntity::class, CaptainVacationResponse::class, $result);
        
        return $respnose;
    }
}
