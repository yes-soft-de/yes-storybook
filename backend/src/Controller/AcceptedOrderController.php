<?php

namespace App\Controller;

use App\AutoMapping;
use App\Service\AcceptedOrderService;
use App\Request\AcceptedOrderCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use stdClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\AcceptedOrderFilterService;

class AcceptedOrderController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $acceptedOrderService;
    private $acceptedOrderFilterService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, AcceptedOrderService $acceptedOrderService, AcceptedOrderFilterService $acceptedOrderFilterService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->acceptedOrderService = $acceptedOrderService;
        $this->acceptedOrderFilterService = $acceptedOrderFilterService;
    }

    /**
     * @Route("/acceptedOrder",   name="createAcceptedOrder", methods={"POST"})
     * @IsGranted("ROLE_CAPTAIN")
     */
    public function createAcceptedOrder(Request $request)
    {   
            $data = json_decode($request->getContent(), true);

            $request = $this->autoMapping->map(stdClass::class, AcceptedOrderCreateRequest::class, (object)$data);

            $request->setCaptainID($this->getUserId());

            $violations = $this->validator->validate($request);
            if (\count($violations) > 0) {
                $violationsString = (string) $violations;

                return new JsonResponse($violationsString, Response::HTTP_OK);
            }

            $response = $this->acceptedOrderService->createAcceptedOrder($request);
            if (is_string($response)) {
                return $this->response($response, self::ACCEPTED_ERROR);
            }
        return $this->response($response, self::CREATE);
    }

     /**
      * @Route("/getAcceptedOrder",        name="getAcceptedOrderByCaptainId", methods={"GET"})
      * @IsGranted("ROLE_CAPTAIN")
      * @return                  JsonResponse
      */
      public function getAcceptedOrderByCaptainId()
      {
          $result = $this->acceptedOrderFilterService->getAcceptedOrderByCaptainId($this->getUserId());
  
          return $this->response($result, self::FETCH);
      }
    
    /**
     * @Route("/getTop5Captains", name="GetTop5Captains",methods={"GET"})
     * @param                                     Request $request
     * @return                                    JsonResponse
     */
    public function getTop5Captains()
    {
        $result = $this->acceptedOrderFilterService->getTop5Captains();

        return $this->response($result, self::FETCH);
    }

     /**
     * @Route("/topCaptains", name="getTopCaptainsInThisMonth",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param                                     Request $request
     * @return                                    JsonResponse
     */
    public function getTopCaptainsInLastMonthDate()
    {
        $result = $this->acceptedOrderFilterService->getTopCaptainsInLastMonthDate();

        return $this->response($result, self::FETCH);
    }
}
