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
    private $supportEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, SupportEntityRepository $supportEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->supportEntityRepository = $supportEntityRepository;
    }

    public function createSupport(SupportCreateRequest $request, $uuid)
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
        return $this->supportEntityRepository->getReports();
    }

    public function getReport($id)
    {
        return $this->supportEntityRepository->getReport($id);
    }

    public function getReportByUuid($uuid)
    {
        return $this->supportEntityRepository->getreortByUuid($uuid);
    }

    public function update($request, $NewMessageStatus)
    {
        if ($request) {
            $entity = $this->supportEntityRepository->find($request->getId());
            
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

    public function updateReportNewMessageStatus($id)
    {
        
            $entity = $this->supportEntityRepository->find($id);
            
            if (!$entity) {
                return null;
            }
            $entity->setNewMessageStatus(false);
        
            $entity = $this->autoMapping->mapToObject(SupportEntity::class, SupportEntity::class, $entity, $entity);

            $this->entityManager->flush();

            return $entity;
    }
}
