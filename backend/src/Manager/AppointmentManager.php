<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\AppointmentEntity;
use App\Repository\AppointmentEntityRepository;
use App\Request\AppointmentCreateRequest;
use App\Request\AppointmentUpdateIsDoneRequest;
use Doctrine\ORM\EntityManagerInterface;

class AppointmentManager
{
    private $autoMapping;
    private $entityManager;
    private $repository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, AppointmentEntityRepository $repository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function create(AppointmentCreateRequest $request)
    {
        $entity = $this->autoMapping->map(AppointmentCreateRequest::class, AppointmentEntity::class, $request);
        $entity->setIsDone(false);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function getAllAppointements()
    {
        return $this->repository->getAllAppointements();
    }

    public function update(AppointmentUpdateIsDoneRequest $request)
    {
        $entity = $this->repository->find($request->getId());

        if (!$entity) {
            return null;
        }
        $entity = $this->autoMapping->mapToObject(AppointmentUpdateIsDoneRequest::class, AppointmentEntity::class, $request, $entity);

        $this->entityManager->flush();

        return $entity;
    }

}
