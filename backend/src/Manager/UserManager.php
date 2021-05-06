<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\UserEntity;
use App\Entity\StoreOwnerProfileEntity;
use App\Entity\CaptainProfileEntity;
use App\Repository\UserEntityRepository;
use App\Repository\StoreOwnerProfileEntityRepository;
use App\Repository\CaptainProfileEntityRepository;
use App\Request\UserProfileCreateRequest;
use App\Request\userProfileUpdateByAdminRequest;
use App\Request\CaptainProfileCreateRequest;
use App\Request\CaptainVacationCreateRequest;
use App\Request\UserProfileUpdateRequest;
use App\Request\CaptainProfileUpdateByAdminRequest;
use App\Request\CaptainProfileUpdateRequest;
use App\Request\UserRegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $autoMapping;
    private $entityManager;
    private $encoder;
    private $userRepository;
    private $captainProfileEntityRepository;
    private $storeOwnerProfileEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, UserEntityRepository $userRepository, CaptainProfileEntityRepository $captainProfileEntityRepository, StoreOwnerProfileEntityRepository $storeOwnerProfileEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $this->captainProfileEntityRepository = $captainProfileEntityRepository;
        $this->storeOwnerProfileEntityRepository = $storeOwnerProfileEntityRepository;
    }

    public function userRegister(UserRegisterRequest $request)
    {
        $userProfile = $this->getUserByUserID($request->getUserID());
        if ($userProfile == null) {

        $userRegister = $this->autoMapping->map(UserRegisterRequest::class, StoreOwnerProfileEntity::class, $request);

        $user = new StoreOwnerProfileEntity($request->getUserID());

        if ($request->getPassword()) {
            $userRegister->setPassword($this->encoder->encodePassword($user, $request->getPassword()));
        }

        $userRegister->setRoles($request->getRoles());

        $this->entityManager->persist($userRegister);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $userRegister;
    }
    else {
        return true;
    }
    }

    public function getUserByUserID($userID)
    {
        return $this->userRepository->getUserByUserID($userID);
    }

    public function userProfileCreate(UserProfileCreateRequest $request, $uuid)
    {
        $request->setUuid($uuid);
        $userProfile = $this->getUserProfileByUserID($request->getUserID());
        if ($userProfile == null) {
            $userProfile = $this->autoMapping->map(UserProfileCreateRequest::class, StoreOwnerProfileEntity::class, $request);

            $userProfile->setStatus('inactive');
            $userProfile->setFree(false);

            $this->entityManager->persist($userProfile);
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $userProfile;
        }
        else {
            return true;
        }
    }

    public function userProfileUpdate(UserProfileUpdateRequest $request)
    {
        $item = $this->storeOwnerProfileEntityRepository->getUserProfile($request->getUserID());

        if ($item) {
            $item = $this->autoMapping->mapToObject(UserProfileUpdateRequest::class, StoreOwnerProfileEntity::class, $request, $item);

            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function userProfileUpdateByAdmin(userProfileUpdateByAdminRequest $request)
    {
        $item = $this->storeOwnerProfileEntityRepository->find($request->getId());

        if ($item) {
            $item = $this->autoMapping->mapToObject(userProfileUpdateByAdminRequest::class, StoreOwnerProfileEntity::class, $request, $item);

            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function getUserProfileByID($id)
    {
        return $this->storeOwnerProfileEntityRepository->getUserProfileByID($id);
    }

    public function getUserProfileByUserID($userID)
    {
        return $this->storeOwnerProfileEntityRepository->getUserProfileByUserID($userID);
    }

    public function getremainingOrders($userID)
    {
        return $this->storeOwnerProfileEntityRepository->getremainingOrders($userID);
    }

    public function createCaptainProfile(CaptainProfileCreateRequest $request, $uuid)
    {
        $request->setUuid($uuid);
        $isCaptainProfile = $this->captainProfileEntityRepository->getcaptainprofileByCaptainID($request->getCaptainID());

        if ($isCaptainProfile == null) {

            $captainProfile = $this->autoMapping->map(CaptainProfileCreateRequest::class, CaptainProfileEntity::class, $request);
            
            //change setStatus to inactive
            $captainProfile->setStatus('active');

            $captainProfile->setIsOnline('active');
            
            $this->entityManager->persist($captainProfile);
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $captainProfile;
        }
        else {
            return true;
        }
    }

    public function UpdateCaptainProfile(CaptainProfileUpdateRequest $request)
    {
        $item = $this->captainProfileEntityRepository->getByCaptainIDForUpdate($request->getUserID());
        if ($item) {
            $item = $this->autoMapping->mapToObject(CaptainProfileUpdateRequest::class, CaptainProfileEntity::class, $request, $item);
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function updateCaptainProfileByAdmin(CaptainProfileUpdateByAdminRequest $request)
    {
        $item = $this->captainProfileEntityRepository->getByCaptainIDForUpdate($request->getCaptainID());
        if ($item) {
            $item = $this->autoMapping->mapToObject(CaptainProfileUpdateByAdminRequest::class, CaptainProfileEntity::class, $request, $item);
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function updateCaptainStateByAdmin(CaptainVacationCreateRequest $request)
    {  
        $item = $this->captainProfileEntityRepository->getByCaptainIDForUpdate($request->getCaptainId());
        
        if ($item) {
            $item = $this->autoMapping->mapToObject(CaptainVacationCreateRequest::class, CaptainProfileEntity::class, $request, $item);
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function getCaptainProfileByCaptainID($captainID)
    {
        return $this->captainProfileEntityRepository->getCaptainProfileByCaptainID($captainID);
    }
    
    public function getCaptainProfileByID($captainProfileId)
    {
        return $this->captainProfileEntityRepository->getCaptainProfileByID($captainProfileId);
    }

    public function getCaptainsInactive()
    {
            return $this->captainProfileEntityRepository->getCaptainsInactive();
    }

    public function captainIsActive($captainID)
    {
        return $this->captainProfileEntityRepository->captainIsActive($captainID);
    }

    public function getCaptainsState($state)
    {
        return $this->captainProfileEntityRepository->getCaptainsState($state);
    }

    public function countpendingCaptains()
    {
        return $this->captainProfileEntityRepository->countpendingCaptains();
    }
   
    public function countOngoingCaptains()
    {
        return $this->captainProfileEntityRepository->countOngoingCaptains();
    }
   
    public function countDayOfCaptains()
    {
        return $this->captainProfileEntityRepository->countDayOfCaptains();
    }
   
    public function getCaptainsInVacation()
    {
        return $this->captainProfileEntityRepository->getCaptainsInVacation();
    }

    public function getCaptainAsArray($id)
    {
        return $this->captainProfileEntityRepository->getCaptainAsArray($id);
    }

    public function getCaptainAsArrayByCaptainId($captainID)
    {
        return $this->captainProfileEntityRepository->getCaptainAsArrayByCaptainId($captainID);
    }
//لا داعي له ولكن تركته لتأكد
    public function getOwners()
    {
        return $this->storeOwnerProfileEntityRepository->getOwners();
    }
//لا داعي له ولكن تركته لتأكد
    public function getCaptains($userID)
    {
        return $this->captainProfileEntityRepository->getCaptains($userID);
    }

    public function getAllStoreOwners()
    {
        return $this->storeOwnerProfileEntityRepository->getAllStoreOwners();
    }
    
    public function getAllCaptains()
    {
        return $this->captainProfileEntityRepository->getAllCaptains();
    }

    public function getcaptainByUuid($uuid)
    {
        return $this->captainProfileEntityRepository->getcaptainByUuid($uuid);
    }

    public function updateCaptainNewMessageStatus($request, $NewMessageStatus)
    {
        if ($request) {
           
            $entity = $this->captainProfileEntityRepository->find($request->getId());
        
            if (!$entity) {
                return null;
            }
            $entity->setNewMessageStatus($NewMessageStatus);
        
            $entity = $this->autoMapping->mapToObject(CaptainProfileEntity::class, CaptainProfileEntity::class, $entity, $entity);
          
            $this->entityManager->flush();

            return $entity;
        }
        return null;
    }

    public function getTop5Captains()
    {        
        return $this->captainProfileEntityRepository->getTop5Captains();
    }

    public function getTopCaptainsInLastMonthDate($fromDate, $toDate)
    {
        return $this->captainProfileEntityRepository->getTopCaptainsInLastMonthDate($fromDate, $toDate);
    }
}
