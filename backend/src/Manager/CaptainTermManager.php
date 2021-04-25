<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\TermsCaptain;
use App\Repository\CaptainTermRepository;
use App\Request\CaptainTermCreateRequest;
use App\Request\CaptainTermUpdateRequest;
use Doctrine\ORM\EntityManagerInterface;

class CaptainTermManager
{
    private $autoMapping;
    private $entityManager;
    private $captainTermRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, CaptainTermRepository $captainTermRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->captainTermRepository = $captainTermRepository;
    }

    public function create(CaptainTermCreateRequest $request)
    {
        $entity = $this->autoMapping->map(CaptainTermCreateRequest::class, TermsCaptain::class, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $entity;
    }

    public function getTermsCaptain()
    {
        return $this->captainTermRepository->getTermsCaptain();
    }

    public function getTermsCaptainById($id) 
    {
        return $this->captainTermRepository->getTermsCaptainById($id) ;
    }

    public function update(CaptainTermUpdateRequest $request)
    {
        $item = $this->captainTermRepository->find($request->getId());
       
        if ($item) {
            $item = $this->autoMapping->mapToObject(CaptainTermUpdateRequest::class, TermsCaptain::class, $request, $item);
            
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }
}
