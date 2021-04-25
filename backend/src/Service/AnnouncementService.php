<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\UpdateEntity;
use App\Manager\AnnouncementManager;
use App\Request\AnnouncementCreateRequest;
use App\Response\AnnouncementResponse;

class AnnouncementService
{
    private $autoMapping;
    private $announcementManager;

    public function __construct(AutoMapping $autoMapping, AnnouncementManager $announcementManager)
    {
        $this->autoMapping = $autoMapping;
        $this->announcementManager = $announcementManager;
    }

    public function create(AnnouncementCreateRequest $request)
    {
        $item = $this->announcementManager->create($request);

        return $this->autoMapping->map(UpdateEntity::class, AnnouncementResponse::class, $item);
    }

    public function update($request)
    {
        $result = $this->announcementManager->update($request);

        return $this->autoMapping->map(UpdateEntity::class, AnnouncementResponse::class, $result);
    }

    public function  getUpdateById($id)
    {
        $result = $this->announcementManager->getUpdateById($id);
        return $this->autoMapping->map(UpdateEntity::class, AnnouncementResponse::class, $result);
  
    }

    public function  getUpdateAll()
    {
        $respons=[];
        $results = $this->announcementManager->getUpdateAll();
       
        foreach ($results as  $result) {
           $respons[]= $this->autoMapping->map('array', AnnouncementResponse::class, $result);
        }
        return $respons;
       
    }

}
