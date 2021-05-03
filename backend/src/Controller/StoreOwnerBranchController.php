<?php

namespace App\Controller;
use App\AutoMapping;
use App\Request\BranchesCreateRequest;
use App\Request\BranchesUpdateRequest;
use App\Request\BranchesDeleteRequest;
use App\Service\StoreOwnerBranchService;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class StoreOwnerBranchController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $storeOwnerBranchService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, StoreOwnerBranchService $storeOwnerBranchService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->storeOwnerBranchService = $storeOwnerBranchService;
    }
    
    /**
     * @Route("branches", name="createBranches", methods={"POST"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @return JsonResponse
     */
    public function createBranches(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, BranchesCreateRequest::class, (object)$data);

        $request->setOwnerID($this->getUserId());

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }
            $result = $this->storeOwnerBranchService->createBranches($request);
            

        return $this->response($result, self::CREATE);
    }

    /**
     * @Route("branches", name="updateBranches", methods={"PUT"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBranche(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(\stdClass::class, BranchesUpdateRequest::class, (object) $data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $result = $this->storeOwnerBranchService->updateBranche($request);

        return $this->response($result, self::UPDATE);
    }

    /**
     * @Route("branches", name="getBranchesByUserId", methods={"GET"})
     * @IsGranted("ROLE_OWNER")
     * @return JsonResponse
     */
    public function getBranchesByUserId()
    {
        $result = $this->storeOwnerBranchService->getBranchesByUserId($this->getUserId());

        return $this->response($result, self::FETCH);
    }

    /**
     * @Route("branche", name="updateActiveBranche", methods={"PUT"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBranchAvailability(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(\stdClass::class, BranchesDeleteRequest::class, (object) $data);

        $violations = $this->validator->validate($request);

        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $result = $this->storeOwnerBranchService->updateBranchAvailability($request);

        return $this->response($result, self::UPDATE);
    }
}
