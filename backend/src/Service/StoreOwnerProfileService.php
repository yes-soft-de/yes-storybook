<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\UserEntity;
use App\Entity\StoreOwnerProfileEntity;
use App\Manager\UserManager;
use App\Request\UserProfileCreateRequest;
use App\Request\UserProfileUpdateRequest;
use App\Request\userProfileUpdateByAdminRequest;
use App\Request\UserRegisterRequest;
use App\Response\UserProfileCreateResponse;
use App\Response\UserProfileResponse;
use App\Response\UserRegisterResponse;
use App\Service\RoomIdHelperService;
use App\Service\StoreOwnerBranchService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class StoreOwnerProfileService
{
    private $autoMapping;
    private $userManager;
    private $storeOwnerBranchService;
    private $params;
    private $roomIdHelperService;

    public function __construct(AutoMapping $autoMapping, UserManager $userManager,  RatingService $ratingService, StoreOwnerBranchService $storeOwnerBranchService, ParameterBagInterface $params, RoomIdHelperService $roomIdHelperService)
    {
        $this->autoMapping = $autoMapping;
        $this->userManager = $userManager;
        $this->ratingService = $ratingService;
        $this->storeOwnerBranchService = $storeOwnerBranchService;
        $this->roomIdHelperService = $roomIdHelperService;

        $this->params = $params->get('upload_base_url') . '/';
    }

    public function userRegister(UserRegisterRequest $request)
    {
        $userRegister = $this->userManager->userRegister($request);
        if ($userRegister instanceof UserEntity) {
            
        return $this->autoMapping->map(UserEntity::class, UserRegisterResponse::class, $userRegister);

        }
        if ($userRegister == true) {
          
            $user = $this->userManager->getUserByUserID($request->getUserID());
            $user['found']="yes";
            return $user;
        }
    }

    public function userProfileCreate(UserProfileCreateRequest $request)
    {
        $uuid = $this->roomIdHelperService->roomIdGenerate();
        $userProfile = $this->userManager->userProfileCreate($request, $uuid);

        if ($userProfile instanceof StoreOwnerProfileEntity) {

            return $this->autoMapping->map(StoreOwnerProfileEntity::class,UserProfileCreateResponse::class, $userProfile);
       }
        if ($userProfile == true) {
          
           return $this->getUserProfileByUserID($request->getUserID());
       }
    }

    public function userProfileUpdate(UserProfileUpdateRequest $request)
    {
        $item = $this->userManager->userProfileUpdate($request);
        
        return $this->autoMapping->map(StoreOwnerProfileEntity::class, UserProfileResponse::class, $item);
    }

    public function userProfileUpdateByAdmin(userProfileUpdateByAdminRequest $request)
    {
        $item = $this->userManager->userProfileUpdateByAdmin($request);

        return $this->autoMapping->map(StoreOwnerProfileEntity::class, UserProfileResponse::class, $item);
    }

    public function getUserProfileByID($id)
    {
        $item = $this->userManager->getUserProfileByID($id);
      
        $item['branches'] = $this->storeOwnerBranchService->branchesByUserId($item['userID']);
        return $this->autoMapping->map('array', UserProfileCreateResponse::class, $item);
    }

    public function getUserProfileByUserID($userID)
    {
        $item = $this->userManager->getUserProfileByUserID($userID);
        $item['branches'] = $this->storeOwnerBranchService->branchesByUserId($userID);

        try {
            if ($item['image'])
            {
                $item['imageURL'] = $item['image'];
                $item['image'] = $this->params.$item['image'];
            }
            $item['baseURL'] = $this->params;
        }
        catch(\Exception $e) {

        }
        
        return $this->autoMapping->map('array', UserProfileCreateResponse::class, $item);
    }

//هذا غير مستخدم ولكن يجب أن تتأكد
    // public function getCaptainsState($state)
    // {
    //     $response = [];
    //     $items = $this->userManager->getCaptainsState($state);

    //     foreach( $items as  $item ) {
           
    //         $item['totalBounce'] = $this->totalBounceCaptain($item['id'], 'admin');
    //         $item['imageURL'] = $item['image'];
    //         $item['image'] = $this->params.$item['image'];
    //         $item['drivingLicenceURL'] = $item['drivingLicence'];
    //         $item['drivingLicence'] = $this->params.$item['drivingLicence'];
    //         $item['baseURL'] = $this->params;

    //         $item['countOrdersDeliverd'] = $this->acceptedOrderService->countAcceptedOrder($item['captainID']);
           
    //         $item['rating'] = $this->ratingService->getRatingByCaptainID($item['captainID']);
            
    //         $response[]  = $this->autoMapping->map('array', CaptainProfileCreateResponse::class, $item);
    //         }
    //     return $response;
    // }

    public function getAllStoreOwners()
    {
        $response = [];
        $owners = $this->userManager->getAllStoreOwners();
        foreach ($owners as $owner) {
            $response[] = $this->autoMapping->map('array', UserProfileCreateResponse::class, $owner);
            }        
        return $response;
    }
}
