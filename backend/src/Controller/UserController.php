<?php

namespace App\Controller;

use App\AutoMapping;
use App\Request\UserProfileCreateRequest;
use App\Request\UserProfileUpdateRequest;
use App\Request\CaptainProfileCreateRequest;
use App\Request\CaptainProfileUpdateRequest;
use App\Request\CaptainProfileUpdateByAdminRequest;
use App\Request\userProfileUpdateByAdminRequest;
use App\Request\UserRegisterRequest;
use App\Service\UserService;
use App\Service\CaptainService;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UserController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $userService;
    private $captainService;
   

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, UserService $userService, CaptainService $captainService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->userService = $userService;
        $this->captainService = $captainService;
        
    }

    /**
     * @Route("/user", name="userRegister", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function userRegister(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        

        $request = $this->autoMapping->map(stdClass::class, UserRegisterRequest::class, (object)$data);

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->userService->userRegister($request);
       
        return $this->response($response, self::CREATE);
    }

    /**
     * @Route("/userprofile", name="userProfileCreate", methods={"POST"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @return JsonResponse
     */
    public function userProfileCreate(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, UserProfileCreateRequest::class, (object)$data);

        $request->setUserID($this->getUserId());

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->userService->userProfileCreate($request);

        return $this->response($response, self::CREATE);
    }

    /**
     * @Route("/userprofile", name="updateUserProfile", methods={"PUT"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserProfile(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, UserProfileUpdateRequest::class, (object)$data);
        $request->setUserID($this->getUserId());

        $response = $this->userService->userProfileUpdate($request);

        return $this->response($response, self::UPDATE);
    }

    /**
     * @Route("/userProfileUpdateByAdmin", name="userProfileUpdateByAdmin", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function userProfileUpdateByAdmin(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, userProfileUpdateByAdminRequest::class, (object)$data);

        $response = $this->userService->userProfileUpdateByAdmin($request);

        return $this->response($response, self::UPDATE);
    }

    /**
     * @Route("/userprofileByID/{id}", name="getUserProfileByID",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function getUserProfileByID($id)
    {
        $response = $this->userService->getUserProfileByID($id);

        return $this->response($response, self::FETCH);
    }

   /**
     * @Route("/userprofilebyuserid/{userId}", name="getUserProfileByID",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function getUserProfile($userId)
    {
        $response = $this->userService->getUserProfileByUserID($userId);

        return $this->response($response, self::FETCH);
    }

    /**
     * @Route("/userprofile", name="getUserProfileByUserId",methods={"GET"})
     * @IsGranted("ROLE_OWNER")
     * @return JsonResponse
     */
    public function getUserProfileByUserID()
    {
        $response = $this->userService->getUserProfileByUserID($this->getUserId());

        return $this->response($response, self::FETCH);
    }

    /**
     * @Route("/remainingOrders", name="GetremainingOrdersSpecificOwner", methods={"GET"})
     * @IsGranted("ROLE_OWNER")
     * @return JsonResponse
     */
    public function getremainingOrders()
    {
        $response = $this->userService->getremainingOrders($this->getUserId());

        return $this->response($response, self::FETCH);
    }

    /**
     * @Route("/captainprofile", name="captainprofileCreate", methods={"POST"})
     * @IsGranted("ROLE_CAPTAIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function createCaptainProfile(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, CaptainProfileCreateRequest::class, (object)$data);

        $request->setCaptainID($this->getUserId());

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->captainService->createCaptainProfile($request);

        return $this->response($response, self::CREATE);
    }

    /**
     * @Route("/captainprofile", name="captainprofileUpdate", methods={"PUT"})
     * @IsGranted("ROLE_CAPTAIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function UpdateCaptainProfile(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, CaptainProfileUpdateRequest::class, (object)$data);
        $request->setUserID($this->getUserId());
        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->captainService->UpdateCaptainProfile($request);

        return $this->response($response, self::UPDATE);
    }
  
    /**
     * @Route("/captainprofileUpdateByAdmin", name="captainprofileUpdateByAdmin", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCaptainProfileByAdmin(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, CaptainProfileUpdateByAdminRequest::class, (object)$data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->captainService->UpdateCaptainProfileByAdmin($request);

        return $this->response($response, self::UPDATE);
    }

    
    /**
     * @Route("/captainprofile", name="getCaptainprofileByCaptainID",methods={"GET"})
     * @IsGranted("ROLE_CAPTAIN")
     *  @return JsonResponse
     */
    public function getCaptainProfileByCaptainID()
    {
        $response = $this->captainService->getCaptainProfileByCaptainID($this->getUserId());

        return $this->response($response, self::FETCH);
    }

    /**
     * @Route("/captainprofile/{captainProfileId}", name="getCaptainprofileBycaptainProfileId",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     *  @return JsonResponse
     */
    public function getCaptainProfileByID($captainProfileId)
    {
        $response = $this->captainService->getCaptainProfileByID($captainProfileId);

        return $this->response($response, self::FETCH);
    }

// هذا الروت مستخدم ضممن السي فور دي 
// لا فائدة من جلب صاحب المتجر الغير مفعل 
// بالنسبة لصاحب المتجر لدينا تفعيل الإشتراك
// تم بناء إند بوينت لجلب الكباتن الغير مفعلين
    // /**
    //  * @Route("/getUserInactive/{userType}", name="getOwnerOrCaptainPending",methods={"GET"})
    //  * @IsGranted("ROLE_ADMIN")
    //  *  @return JsonResponse
    //  */
    // public function getUserInactive($userType)
    // {
    //     $response = $this->userService->getUserInactive($userType);

    //     return $this->response($response, self::FETCH);
    // }

    /**
     * @Route("/getcaptainsinactive", name="getCaptainsPending",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     *  @return JsonResponse
     */
    public function getCaptainsInactive()
    {
        $response = $this->captainService->getCaptainsInactive();

        return $this->response($response, self::FETCH);
    }
//هذا غير مستخدم ولكن يجب أن تتأكد
    // /**
    //  * @Route("/getCaptainsState/{state}", name="getCaptainsState",methods={"GET"})
    //  * @IsGranted("ROLE_ADMIN")
    //  *  @return JsonResponse
    //  */
    // public function getCaptainsState($state)
    // {
    //     $response = $this->userService->getCaptainsState($state);

    //     return $this->response($response, self::FETCH);
    // }

    /**
     * @Route("/dashboardCaptains", name="dashboardCaptains",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param                                     Request $request
     * @return                                    JsonResponse
     */
    public function dashboardCaptains()
    {
        $result = $this->captainService->dashboardCaptains();

        return $this->response($result, self::FETCH);
    }

    /**
     * @Route("/getDayOfCaptains", name="getDayOfCaptains",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCaptainsInVacation()
    {
        $result = $this->captainService->getCaptainsInVacation();

        return $this->response($result, self::FETCH);
    }

    /**
     * @Route("/totalBounceCaptain/{captainProfileId}", name="TotalBounceCaptain",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCaptainFinancialAccountDetailsByCaptainProfileId($captainProfileId)
    {
        $result = $this->captainService->getCaptainFinancialAccountDetailsByCaptainProfileId($captainProfileId);

        return $this->response($result, self::FETCH);
    }

     /**
     * @Route("/storeowners", name="getAllStoreOwners",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function getAllStoreOwners()
    {
        $response = $this->userService->getAllStoreOwners();

        return $this->response($response, self::FETCH);
    }
     /**
     * @Route("/captains", name="getCaptains",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function getAllCaptains()
    {
        $response = $this->captainService->getAllCaptains();

        return $this->response($response, self::FETCH);
    }

     /**
     * @Route("/captainmybalance", name="getCaptainMyBalance",methods={"GET"})
     * @IsGranted("ROLE_CAPTAIN")
     *  @return JsonResponse
     */
    public function getCaptainFinancialAccountDetailsByCaptainId()
    {
        $response = $this->captainService->getCaptainFinancialAccountDetailsByCaptainId($this->getUserId());

        return $this->response($response, self::FETCH);
    }

     /**
     * @Route("/remainingcaptain", name="TheRemainingCaptainHasAPayment",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function remainingcaptain()
    {
        $response = $this->captainService->remainingcaptain();

        return $this->response($response, self::FETCH);
    }

    /**
     * @Route("/captainupdatenewmessagestatus", name="captainUpdateNewMessageStatus", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCaptainNewMessageStatus(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class,CaptainProfileUpdateByAdminRequest::class,(object)$data);
        
        $response = $this->captainService->updateCaptainNewMessageStatus($request,false);

        return $this->response($response, self::CREATE);
    }
}
