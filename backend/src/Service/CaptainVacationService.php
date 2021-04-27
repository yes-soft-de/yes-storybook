<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\CaptainVacationEntity;
use App\Manager\CaptainVacationManager;
use App\Request\CaptainVacationCreateRequest;
use App\Response\CaptainVacationResponse;
use App\Service\UserService;

class CaptainVacationService
{
    private $autoMapping;
    private $captainVacationManager;
    private $userService;

    public function __construct(AutoMapping $autoMapping, CaptainVacationManager $captainVacationManager, UserService $userService)
    {
        $this->autoMapping = $autoMapping;
        $this->userService = $userService;
        $this->captainVacationManager = $captainVacationManager;
    }

    public function createCaptainVacation(CaptainVacationCreateRequest $request)
    {
        $result = $this->captainVacationManager->createCaptainVacation($request);
        if ($result) {
           $this->userService->captainvacationbyadmin($request); 
        }
        $respnose = $this->autoMapping->map(CaptainVacationEntity::class, CaptainVacationResponse::class, $result);
        
        return $respnose;
    }
}
