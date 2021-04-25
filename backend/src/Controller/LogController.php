<?php

namespace App\Controller;

use App\Service\LogService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LogController extends BaseController
{
    private $logService;

    public function __construct(SerializerInterface $serializer, LogService $logService)
    {
        parent::__construct($serializer);
        $this->logService = $logService;
    } 
    
    /**
      * @Route("/record/{orderId}", name="GetRecordByOrderId", methods={"GET"})
      * @param                     Request $request
      * @return                    JsonResponse
      */
      public function getRecordByOrderId($orderId)
      {
          $result = $this->logService->getRecordByOrderId($orderId);
  
          return $this->response($result, self::FETCH);
      }

    /**
      * @Route("/records/{orderId}", name="GetRecordsByOrderId", methods={"GET"})
      * @param                     Request $request
      * @return                    JsonResponse
      */
      public function getRecordsByOrderId($orderId)
      {
          $result = $this->logService->getRecordsWithcompletionTime($orderId);
  
          return $this->response($result, self::FETCH);
      }
}
