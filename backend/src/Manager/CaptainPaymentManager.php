<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\CaptainPaymentEntity;
use App\Repository\CaptainPaymentEntityRepository;
use App\Request\CaptainPaymentCreateRequest;
// use App\Request\RatingUpdateRequest;
use Doctrine\ORM\EntityManagerInterface;

class CaptainPaymentManager
{
    private $autoMapping;
    private $entityManager;
    private $repository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, CaptainPaymentEntityRepository $repository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function createCaptainPayment(CaptainPaymentCreateRequest $request)
    {
        $entity = $this->autoMapping->map(CaptainPaymentCreateRequest::class, CaptainPaymentEntity::class, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function getpayments($captainId)
    {
        return $this->repository->getpayments($captainId);
    }

    public function getSumAmount($captainId)
    {
        return $this->repository->getSumAmount($captainId);
    }
}
