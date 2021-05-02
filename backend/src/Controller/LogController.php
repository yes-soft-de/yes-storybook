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
      * @param Request $request
      * @return JsonResponse
      */
      public function getLogByOrderId($orderId)
      {
          $result = $this->logService->getLogByOrderId($orderId);
  
          return $this->response($result, self::FETCH);
      }
      
    /**
      * @Route("/records/{orderId}", name="GetRecordsByOrderId", methods={"GET"})
      * @param Request $request
      * @return JsonResponse
      */
      public function getLogsByOrderId($orderId)
      {
          $result = $this->logService->getLogsWithcompletionTime($orderId);
  
          return $this->response($result, self::FETCH);
      }

     /**
      * @Route("/records", name="GetLogsByUserId", methods={"GET"})
      * @param Request $request
      * @return JsonResponse
      */
    public function getLogsByUserID()
    {    
        if( $this->isGranted('ROLE_OWNER') ) {
         $result = $this->logService->getLogsByStoreOwner($this->getUserId());
        }

        if( $this->isGranted('ROLE_CAPTAIN') ) {
         $result = $this->logService->getLogsByCaptain($this->getUserId());
        }
        
        return $this->response($result, self::FETCH);
    }

}
