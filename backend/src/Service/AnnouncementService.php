<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\AnnouncementEntity;
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

    public function createAnnouncement(AnnouncementCreateRequest $request):object
    {
        $item = $this->announcementManager->createAnnouncement($request);

        return $this->autoMapping->map(AnnouncementEntity::class, AnnouncementResponse::class, $item);
    }

    public function update($request):object
    {
        $result = $this->announcementManager->update($request);

        return $this->autoMapping->map(AnnouncementEntity::class, AnnouncementResponse::class, $result);
    }

    public function  getAnnouncementById($id):object
    {
        $result = $this->announcementManager->getAnnouncementById($id);
        return $this->autoMapping->map(AnnouncementEntity::class, AnnouncementResponse::class, $result);
  
    }

    public function  getAllAnnouncements():array
    {
        $respons=[];
        $results = $this->announcementManager->getAllAnnouncements();
       
        foreach ($results as  $result) {
           $respons[]= $this->autoMapping->map('array', AnnouncementResponse::class, $result);
        }
        return $respons;
       
    }

}
