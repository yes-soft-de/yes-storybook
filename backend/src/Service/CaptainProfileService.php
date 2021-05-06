<?php

namespace App\Service;

use App\AutoMapping;
use App\Entity\CaptainProfileEntity;
use App\Request\CaptainProfileCreateRequest;
use App\Request\CaptainVacationCreateRequest;
use App\Request\CaptainProfileUpdateRequest;
use App\Request\CaptainProfileUpdateByAdminRequest;
use App\Response\CaptainProfileCreateResponse;
use App\Response\CaptainFinancialAccountDetailsResponse;
use App\Service\CaptainPaymentService;
use App\Service\RoomIdHelperService;
use App\Service\AcceptedOrderService;
use App\Service\RatingService;
use App\Service\DateFactoryService;
use App\Manager\UserManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class CaptainProfileService
{
    private $autoMapping;
    private $userManager;
    // private $acceptedOrderService;
    private $ratingService;
    private $params;
    private $captainPaymentService;
    private $roomIdHelperService;
    private $dateFactoryService;
    private $acceptedOrderService;

    public function __construct(AutoMapping $autoMapping, ParameterBagInterface $params, CaptainPaymentService $captainPaymentService,  RoomIdHelperService $roomIdHelperService, UserManager $userManager,
    //  AcceptedOrderService $acceptedOrderService,
      RatingService $ratingService, DateFactoryService $dateFactoryService, AcceptedOrderService $acceptedOrderService)
    {
        $this->autoMapping = $autoMapping;
        $this->captainPaymentService = $captainPaymentService;
        $this->roomIdHelperService = $roomIdHelperService;
        $this->userManager = $userManager;
        // $this->acceptedOrderService = $acceptedOrderService;
        $this->ratingService = $ratingService;
        $this->dateFactoryService = $dateFactoryService;
        $this->acceptedOrderService = $acceptedOrderService;

        $this->params = $params->get('upload_base_url') . '/';
    }

    public function createCaptainProfile(CaptainProfileCreateRequest $request)
    { 
        $uuid = $this->roomIdHelperService->roomIdGenerate();
        $captainProfile = $this->userManager->createCaptainProfile($request, $uuid);
        
        if ($captainProfile instanceof CaptainProfileEntity) {
           
            return $this->autoMapping->map(CaptainProfileEntity::class, CaptainProfileCreateResponse::class, $captainProfile);
        }
        if ($captainProfile == true) {
            return $this->getCaptainProfileByCaptainID($request->getCaptainID());
        }
    }

    public function UpdateCaptainProfile(CaptainProfileUpdateRequest $request)
    {
        $item = $this->userManager->UpdateCaptainProfile($request);
        
        return $this->autoMapping->map(CaptainProfileEntity::class, CaptainProfileCreateResponse::class, $item);
    }

    public function updateCaptainProfileByAdmin(CaptainProfileUpdateByAdminRequest $request)
    {
        $item = $this->userManager->updateCaptainProfileByAdmin($request);

        return $this->autoMapping->map(CaptainProfileEntity::class, CaptainProfileCreateResponse::class, $item);
    }

    public function updateCaptainStateByAdmin(CaptainVacationCreateRequest $request)
    {
        return $this->userManager->updateCaptainStateByAdmin($request);

    }

    public function getCaptainProfileByCaptainID($captainID):object
    {
        $response=(object)[];

        $item = $this->userManager->getCaptainProfileByCaptainID($captainID);

        $bounce = $this->getCaptainFinancialAccountDetailsByCaptainId($captainID);

        $countOrdersDeliverd = $this->acceptedOrderService->countCaptainOrdersDelivered($captainID);

        $item['imageURL'] = $item['image'];
        $item['image'] = $this->params.$item['image'];
        $item['drivingLicenceURL'] = $item['drivingLicence'];
        $item['drivingLicence'] = $this->params.$item['drivingLicence'];
        $item['baseURL'] = $this->params;
        $item['rating'] = $this->ratingService->getRatingByCaptainID($captainID);

        $response = $this->autoMapping->map('array', CaptainProfileCreateResponse::class, $item);

        $response->bounce = $bounce;
        $response->countOrdersDeliverd = $countOrdersDeliverd;

        return $response;
    }

    public function getCaptainProfileByID($captainProfileId)
    {
        $response=[];
        $totalBounce=[];
        $countOrdersDeliverd=[];
        $item = $this->userManager->getCaptainProfileByID($captainProfileId);
        if($item) {
            $totalBounce = $this->getCaptainFinancialAccountDetailsByCaptainProfileId($item['id']);
            $item['imageURL'] = $item['image'];
            $item['image'] = $this->params.$item['image'];
            $item['drivingLicenceURL'] = $item['drivingLicence'];
            $item['drivingLicence'] = $this->params.$item['drivingLicence'];
            $item['baseURL'] = $this->params;
            $countOrdersDeliverd = $this->acceptedOrderService->countCaptainOrdersDelivered($item['captainID']);

            $item['rating'] = $this->ratingService->getRatingByCaptainID($item['captainID']);
        }
        $response =  $this->autoMapping->map('array', CaptainProfileCreateResponse::class, $item);
        if($item) {
            $response->totalBounce = $totalBounce;
            $response->countOrdersDeliverd = $countOrdersDeliverd;
        }
        return $response;
    }

    public function getCaptainsInactive():array
    {
        $response = [];
        $items = $this->userManager->getCaptainsInactive();
        foreach( $items as  $item ) {
            $item['imageURL'] = $item['image'];
            $item['image'] = $this->params.$item['image'];
            $item['drivingLicenceURL'] = $item['drivingLicence'];
            $item['drivingLicence'] = $this->params.$item['drivingLicence'];
            $item['baseURL'] = $this->params;
            $response[]  = $this->autoMapping->map('array', CaptainProfileEntity::class, $item);
            }
     return $response;
    }
    
    public function captainIsActive($captainID)
    {
        $item = $this->userManager->captainIsActive($captainID);
        if ($item) {
          return  $item[0]['status'];
        }

        return $item ;
     }

     public function dashboardCaptains():array
     {
         $response = [];

         $response[] = $this->userManager->countpendingCaptains();
         $response[] = $this->userManager->countOngoingCaptains();
         $response[] = $this->userManager->countDayOfCaptains();

         $top5Captains = $this->getTop5Captains();
      
         foreach ($top5Captains as $item) {
           
            $item['imageURL'] = $item['image'];
            $item['image'] = $this->params.$item['image'];
            $item['baseURL'] = $this->params;   

            $response[]  = $this->autoMapping->map('array',CaptainProfileCreateResponse::class,  $item);
         }         
         return $response;
     }

     public function getCaptainsInVacation():array
     {
         $response = [];

         $dayOfCaptains = $this->userManager->getCaptainsInVacation();
      
         foreach ($dayOfCaptains as $item) {
            $item['imageURL'] = $item['image'];
            $item['image'] = $this->params.$item['image'];
            $item['drivingLicenceURL'] = $item['drivingLicence'];
            $item['drivingLicence'] = $this->params.$item['drivingLicence'];
            $item['baseURL'] = $this->params;

            $response[]  = $this->autoMapping->map('array',CaptainProfileCreateResponse::class,  $item);
         }         
         return $response;
     }
 
     public function getCaptainFinancialAccountDetailsByCaptainProfileId($captainProfileId):array 
    {
        $response = [];
        //get captain info as Array
        $item = $this->userManager->getCaptainAsArray($captainProfileId);
        
        if ($item) {
            $sumPayments = $this->captainPaymentService->getSumPayments($item[0]['captainID']);
            $payments = $this->captainPaymentService->getpayments($item[0]['captainID']);
            $countAcceptedOrder = $this->acceptedOrderService->countCaptainOrdersDelivered($item[0]['captainID']);

             $item['countOrdersDeliverd'] = $countAcceptedOrder[0]['countOrdersDeliverd'];
             //bounce = total bounce
             $item['bounce'] = $item[0]['bounce'] *  $item['countOrdersDeliverd'];
             $item['sumPayments'] = $sumPayments[0]['sumPayments'];
             $item['NetProfit'] = $item['bounce'] + $item[0]['salary'];
             $item['total'] = $item['sumPayments'] - $item['NetProfit'];
             $item['payments'] = $payments;

             $response[] = $this->autoMapping->map('array', CaptainFinancialAccountDetailsResponse::class,  $item);  
        }
        return $response;
    }

     public function getCaptainFinancialAccountDetailsByCaptainId($captainId):array
    {
        $response=[];

        $item = $this->userManager->getCaptainAsArrayByCaptainId($captainId);
       
        $sumPayments = $this->captainPaymentService->getSumPayments($captainId);
        $payments = $this->captainPaymentService->getpayments($captainId);
        
        if ($item) {
             $countAcceptedOrder = $this->acceptedOrderService->countCaptainOrdersDelivered($item[0]['captainID']);
             $item['countOrdersDeliverd'] = $countAcceptedOrder[0]['countOrdersDeliverd'];
             $item['bounce'] = $item[0]['bounce'] * $item['countOrdersDeliverd'];
             $item['sumPayments'] = $sumPayments[0]['sumPayments'];
             $item['NetProfit'] = $item['bounce'] + $item[0]['salary'];
             $item['total'] = $item['NetProfit'] - $item['sumPayments'];
             $item['payments'] = $payments;

             $response[] = $this->autoMapping->map('array', CaptainFinancialAccountDetailsResponse::class,  $item);
            
        }
        return $response;
    }

    public function getAllCaptains():array
    {
        $response = [];
        $captains = $this->userManager->getAllCaptains();
        foreach ($captains as $captain) {
            $captain['imageURL'] = $captain['image'];
            $captain['image'] = $this->params.$captain['image'];
            $captain['drivingLicenceURL'] = $captain['drivingLicence'];
            $captain['drivingLicence'] = $this->params.$captain['drivingLicence'];
            $captain['baseURL'] = $this->params;

            $response[]  = $this->autoMapping->map('array',CaptainProfileCreateResponse::class,  $captain);
            }       
        return $response;
    }

    public function specialLinkCheck($bool)
    {
        if (!$bool)
        {
            return $this->params;
        }
    }

    public function getCaptainsWithUnfinishedPayments()
    {
        $response = [];
        $result = [];
        $captains = $this->userManager->getAllCaptains();
         
        foreach ($captains as $captain) {
                
                $item = $this->userManager->getCaptainProfileByID($captain['id']);
       
                 $totalBounce = $this->getCaptainFinancialAccountDetailsByCaptainProfileId($item['id']);
                 $total=(array)$totalBounce;
                 $captain['totalBounce'] = $total;
        
                if ($captain['totalBounce']['total'] < 0 ){
                
                $response[] =  $this->autoMapping->map('array', CaptainProfileCreateResponse::class, $captain);
            }
        } 
        $result['response']=$response;
        return $result;
    }

    public function updateCaptainNewMessageStatus($request, $NewMessageStatus)
    {
        $item = $this->userManager->getcaptainByUuid($request->getRoomID());
   
       $response = $this->userManager->updateCaptainNewMessageStatus($item, $NewMessageStatus);
    
       return  $this->autoMapping->map(CaptainProfileEntity::class, CaptainProfileCreateResponse::class, $response);
    }

    public function getTop5Captains()
    {
       return $this->userManager->getTop5Captains();
    }

    public function getTopCaptainsInLastMonthDate():array
    {
       $response = [];
       $date = $this->dateFactoryService->returnLastMonthDate();
       $topCaptains = $this->userManager->getTopCaptainsInLastMonthDate($date[0],$date[1]);
     
        foreach ($topCaptains as $topCaptain) {
            $topCaptain['imageURL'] = $topCaptain['image'];
            $topCaptain['image'] = $this->params.$topCaptain['image'];
            $topCaptain['drivingLicenceURL'] = $topCaptain['drivingLicence'];
            $topCaptain['drivingLicence'] = $this->params.$topCaptain['drivingLicence'];
            $topCaptain['baseURL'] = $this->params;
            $response[] = $this->autoMapping->map('array', CaptainProfileCreateResponse::class, $topCaptain);
        }
    
       return $response;
   }
}
