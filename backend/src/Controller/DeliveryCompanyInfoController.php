<?php

namespace App\Controller;
use App\AutoMapping;
use App\Request\DeliveryCompanyInfoRequest;
use App\Request\DeliveryCompanyInfoUpdateRequest;
use App\Service\DeliveryCompanyInfoService;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class DeliveryCompanyInfoController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $deliveryCompanyInfoService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, DeliveryCompanyInfoService $deliveryCompanyInfoService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->deliveryCompanyInfoService = $deliveryCompanyInfoService;
    }

    /**
     * @Route("companyinfo", name="createCompanyInfo", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, DeliveryCompanyInfoRequest::class, (object)$data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }
        $result = $this->deliveryCompanyInfoService->create($request);
            

        return $this->response($result, self::CREATE);
    }

     /**
     * @Route("companyinfo", name="updateCompanyInfo", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(\stdClass::class, DeliveryCompanyInfoUpdateRequest::class, (object) $data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $result = $this->deliveryCompanyInfoService->update($request);

        return $this->response($result, self::UPDATE);
    }

     /**
     * @Route("companyinfo/{id}", name="getcompanyinfoById", methods={"GET"})
     * @return JsonResponse
     */
    public function getcompanyinfoById($id)
    {
        $result = $this->deliveryCompanyInfoService->getcompanyinfoById($id);

        return $this->response($result, self::FETCH);
    }

     /**
     * @Route("companyinfoall", name="getcompanyinfoAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getcompanyinfoAll()
    {
        $result = $this->deliveryCompanyInfoService->getcompanyinfoAll();

        return $this->response($result, self::FETCH);
    }

     /**
     * @Route("companyinfoforuser", name="getcompanyinfoAllforUser", methods={"GET"})
     * @return JsonResponse
     */
    public function getcompanyinfoAllForUser()
    {
        if ($this->isGranted('ROLE_OWNER')) {
             $result = $this->deliveryCompanyInfoService->getAllCompanyInfoForStoreOwner($this->getUserId());
        }

        if ($this->isGranted('ROLE_CAPTAIN')) {
             $result = $this->deliveryCompanyInfoService->getAllCompanyInfoForCaptain($this->getUserId());
        }
        return $this->response($result, self::FETCH);
    }
}
