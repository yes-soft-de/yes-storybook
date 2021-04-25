<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\DatingEntity;
use App\Manager\AppointmentManager;
use App\Request\AppointmentCreateRequest;
use App\Response\AppointmentResponse;

class AppointmentService
{
    private $autoMapping;
    private $appointmentManager;

    public function __construct(AutoMapping $autoMapping, AppointmentManager $appointmentManager)
    {
        $this->autoMapping = $autoMapping;
        $this->appointmentManager = $appointmentManager;
    }

    public function create(AppointmentCreateRequest $request)
    {
        $reprot = $this->appointmentManager->create($request);

        return $this->autoMapping->map(DatingEntity::class, AppointmentResponse::class, $reprot);
    }

    public function datings()
    {
        $response = [];
        $items = $this->appointmentManager->datings();
        foreach ($items as $item) {
        $response[] =  $this->autoMapping->map('array', AppointmentResponse::class, $item);
        }
        return $response;
    }

    public function update($request)
    {
        $result = $this->appointmentManager->update($request);

        return $this->autoMapping->map(DatingEntity::class, AppointmentResponse::class, $result);
    }
}
