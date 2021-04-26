<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\SupportEntity;
use App\Repository\SupportEntityRepository;
use App\Request\SupportCreateRequest;
use Doctrine\ORM\EntityManagerInterface;

class SupportManager
{
    private $autoMapping;
    private $entityManager;
    private $repository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, SupportEntityRepository $repository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function create(SupportCreateRequest $request, $uuid)
    {
        $request->setUuid($uuid);
        $entity = $this->autoMapping->map(SupportCreateRequest::class, SupportEntity::class, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function getReports()
    {
        return $this->repository->getReports();
    }

    public function getReport($id)
    {
        return $this->repository->getReport($id);
    }

    public function getReportByUuid($uuid)
    {
        return $this->repository->getreortByUuid($uuid);
    }

    public function update($request, $NewMessageStatus)
    {
        if ($request) {
            $entity = $this->repository->find($request->getId());
            
            if (!$entity) {
                return null;
            }
            $entity->setNewMessageStatus($NewMessageStatus);
        
            $entity = $this->autoMapping->mapToObject(SupportEntity::class, SupportEntity::class, $entity, $entity);

            $this->entityManager->flush();

            return $entity;
        }
        return null;
    }

    public function reportUpdateNewMeessageStatus($id)
    {
        
            $entity = $this->repository->find($id);
            
            if (!$entity) {
                return null;
            }
            $entity->setNewMessageStatus(false);
        
            $entity = $this->autoMapping->mapToObject(SupportEntity::class, SupportEntity::class, $entity, $entity);

            $this->entityManager->flush();

            return $entity;
    }
}
