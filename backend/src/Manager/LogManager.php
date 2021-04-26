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
    private $repository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, LogEntityRepository $repository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
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

    public function getRecordByOrderId($orderId)
    {
        return $this->repository->getRecordByOrderId($orderId);
    }

    public function getRecordsByOrderId($orderId)
    {
        return $this->repository->getRecordsByOrderId($orderId);
    }

    public function getFirstDate($orderId)
    {
        return $this->repository->getFirstDate($orderId);
    }
    
    public function getLastDate($orderId)
    {
        return $this->repository->getLastDate($orderId);
    }
}
