<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\VacationsEntity;
use App\Repository\CaptainVacationEntityRepository;
use App\Request\CaptainVacationCreateRequest;
use Doctrine\ORM\EntityManagerInterface;

class CaptainVacationManager
{
    private $autoMapping;
    private $entityManager;
    private $captainVacationRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, CaptainVacationEntityRepository $captainVacationRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->captainVacationRepository = $captainVacationRepository;
    }

    public function create(CaptainVacationCreateRequest $request)
    {
        $entity = $this->autoMapping->map(CaptainVacationCreateRequest::class, VacationsEntity::class, $request);

        $entity->setStartDate($request->getStartDate());
        $entity->setEndDate($request->getEndDate());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }
}
