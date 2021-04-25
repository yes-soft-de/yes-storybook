<?php

namespace App\Service;

use App\AutoMapping;
use App\Manager\CaptainPaymentManager;
use App\Entity\PaymentsCaptainEntity;
use App\Request\CaptainPaymentCreateRequest;
use App\Response\CaptainPaymentCreateResponse;
use App\Service\BankService;

class CaptainPaymentService
{
    private $autoMapping;
    private $captainPaymentManager;
    private $bankService;

    public function __construct(AutoMapping $autoMapping, CaptainPaymentManager $captainPaymentManager, BankService $bankService)
    {
        $this->autoMapping = $autoMapping;
        $this->captainPaymentManager = $captainPaymentManager;
        $this->bankService = $bankService;
    }

    public function create(CaptainPaymentCreateRequest $request)
    {
        $item = $this->captainPaymentManager->create($request);

        return $this->autoMapping->map(PaymentsCaptainEntity::class, CaptainPaymentCreateResponse::class, $item);
    }

    public function getpayments($captainId)
    {
       return $this->captainPaymentManager->getpayments($captainId);
        }
    public function getSumAmount($captainId)
    {
       return $this->captainPaymentManager->getSumAmount($captainId);
    }
}
