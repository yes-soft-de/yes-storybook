<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\VacationsEntity;
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

    public function create(CaptainVacationCreateRequest $request)
    {
        $result = $this->captainVacationManager->create($request);
        if ($result) {
           $this->userService->captainvacationbyadmin($request); 
        }
        $respnose = $this->autoMapping->map(VacationsEntity::class, CaptainVacationResponse::class, $result);
        
        return $respnose;
    }
}
