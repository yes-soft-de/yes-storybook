<?php

namespace App\Controller;

use App\AutoMapping;
use App\Request\CaptainPaymentCreateRequest;
use App\Service\CaptainPaymentService;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CaptainPaymentController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $captainPaymentService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, CaptainPaymentService $captainPaymentService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->captainPaymentService = $captainPaymentService;
    }
    
    /**
     * @Route("/paymentcaptain", name="createpaymentCaptain", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
            $data = json_decode($request->getContent(), true);

            $request = $this->autoMapping->map(stdClass::class, CaptainPaymentCreateRequest::class, (object)$data);

            $violations = $this->validator->validate($request);

            if (\count($violations) > 0) {
                $violationsString = (string) $violations;

                return new JsonResponse($violationsString, Response::HTTP_OK);
            }
            $result = $this->captainPaymentService->create($request);

        return $this->response($result, self::CREATE);
    }

     /**
      * @Route("/paymentscaptain", name="GetpaymentsForCaptain", methods={"GET"})
      * @IsGranted("ROLE_CAPTAIN")
      * @param                     Request $request
      * @return                    JsonResponse
      */
      public function getpaymentsForCaptain()
      {
          $result = $this->captainPaymentService->getpayments($this->getUserId());
  
          return $this->response($result, self::FETCH);
      }
    
}
