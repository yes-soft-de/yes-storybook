<?php


namespace App\Controller;
use App\AutoMapping;
use App\Request\AppointmentCreateRequest;
use App\Request\AppointmentUpdateIsDoneRequest;
use App\Service\AppointmentService;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AppointmentController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $appointmentService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, AppointmentService $appointmentService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->appointmentService = $appointmentService;
    }
    
    /**
     * @Route("dating", name="createdating", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, AppointmentCreateRequest::class, (object)$data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }
            $result = $this->appointmentService->create($request);
            

        return $this->response($result, self::CREATE);
    }

    /**
      * @Route("/datings", name="Getdatings", methods={"GET"})
      * @IsGranted("ROLE_ADMIN")
      * @param                     Request $request
      * @return                    JsonResponse
      */
      public function getAllAppointements()
      {
          $result = $this->appointmentService->getAllAppointements();
  
          return $this->response($result, self::FETCH);
      }

      /**
     * @Route("dating", name="updateDatingIsDone", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(\stdClass::class, AppointmentUpdateIsDoneRequest::class, (object) $data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $result = $this->appointmentService->update($request);

        return $this->response($result, self::UPDATE);
    }
}
