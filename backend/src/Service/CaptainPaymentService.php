<?php

namespace App\Service;

use App\AutoMapping;
use App\Manager\CaptainPaymentManager;
use App\Entity\CaptainPaymentEntity;
use App\Request\CaptainPaymentCreateRequest;
use App\Response\CaptainPaymentCreateResponse;

class CaptainPaymentService
{
    private $autoMapping;
    private $captainPaymentManager;

    public function __construct(AutoMapping $autoMapping, CaptainPaymentManager $captainPaymentManager)
    {
        $this->autoMapping = $autoMapping;
        $this->captainPaymentManager = $captainPaymentManager;
    }

    public function createCaptainPayment(CaptainPaymentCreateRequest $request)
    {
        $item = $this->captainPaymentManager->createCaptainPayment($request);

        return $this->autoMapping->map(CaptainPaymentEntity::class, CaptainPaymentCreateResponse::class, $item);
    }

    public function getpayments($captainId)
    {
       return $this->captainPaymentManager->getpayments($captainId);
    }

    public function getSumPayments($captainId)
    {
       return $this->captainPaymentManager->getSumPayments($captainId);
    }
}
