<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\LogEntity;
use App\Repository\LogEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class LogManager
{
    private $autoMapping;
    private $entityManager;
    private $logEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, LogEntityRepository $logEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->logEntityRepository = $logEntityRepository;
    }

    public function create($record)
    {
        $logEntity = $this->autoMapping->map('array', LogEntity::class, $record);
        $logEntity->setDate($logEntity->getDate());
        
        $this->entityManager->persist($logEntity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $logEntity;
    }

    public function getLogByOrderId($orderId)
    {
        return $this->logEntityRepository->getLogByOrderId($orderId);
    }

    public function getLogsByOrderId($orderId)
    {
        return $this->logEntityRepository->getLogsByOrderId($orderId);
    }

    public function getFirstDate($orderId)
    {
        return $this->logEntityRepository->getFirstDate($orderId);
    }
    
    public function getLastDate($orderId)
    {
        return $this->logEntityRepository->getLastDate($orderId);
    }
}
